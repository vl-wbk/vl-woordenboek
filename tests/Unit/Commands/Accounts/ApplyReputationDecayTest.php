<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Articles;

use App\Console\Commands\Users\ApplyReputationDecay;
use App\Models\User;

describe('getDecayConfig', function (): void {
    it('returns no decay within the grace period', function (): void {
        $command = new ApplyReputationDecay();

        expect($command->getDecayConfig(0))->toMatchArray(['rate' => 0.0, 'frequency' => null])
            ->and($command->getDecayConfig(14))->toMatchArray(['rate' => 0.0, 'frequency' => null]);
    });

    it('applies the 1% weekly tier between 15 and 30 days inactive', function (): void {
        $command = new ApplyReputationDecay();

        expect($command->getDecayConfig(15))->toMatchArray(['rate' => 0.01, 'frequency' => 7])
            ->and($command->getDecayConfig(30))->toMatchArray(['rate' => 0.01, 'frequency' => 7]);
    });

    it('applies the 3% weekly tier between 31 and 60 days inactive', function (): void {
        $command = new ApplyReputationDecay();

        expect($command->getDecayConfig(31))->toMatchArray(['rate' => 0.03, 'frequency' => 7])
            ->and($command->getDecayConfig(60))->toMatchArray(['rate' => 0.03, 'frequency' => 7]);
    });

    it('applies the 5% tri-daily tier beyond 60 days inactive', function (): void {
        $command = new ApplyReputationDecay();

        expect($command->getDecayConfig(61))->toMatchArray(['rate' => 0.05, 'frequency' => 3])
            ->and($command->getDecayConfig(999))->toMatchArray(['rate' => 0.05, 'frequency' => 3]);
    });
});

describe('applyDecay', function (): void {
    it('skips users with no last_seen_at', function (): void {
        $user = User::factory()->create([
            'last_seen_at' => null,
            'reputation'   => 100,
        ]);

        $command = new ApplyReputationDecay();
        $command->applyDecay($user);

        expect($user->fresh()->reputation)->toBe(100);
    });

    it('skips users still within the grace period', function (): void {
        $user = User::factory()->create([
            'last_seen_at'    => now()->subDays(10),
            'last_decayed_at' => null,
            'reputation'      => 100,
        ]);

        $command = new ApplyReputationDecay();
        $command->applyDecay($user);

        expect($user->fresh()->reputation)->toBe(100);
    });

    it('skips users who were decayed too recently', function (): void {
        $user = User::factory()->create([
            'last_seen_at'    => now()->subDays(20), // 1% / 7-day tier
            'last_decayed_at' => now()->subDays(3),  // only 3 days ago, needs 7
            'reputation'      => 100,
        ]);

        $command = new ApplyReputationDecay();
        $command->applyDecay($user);

        expect($user->fresh()->reputation)->toBe(100);
    });

    it('deducts the correct penalty when decay is due', function (): void {
        $user = User::factory()->create([
            'last_seen_at'    => now()->subDays(20), // 1% / 7-day tier
            'last_decayed_at' => now()->subDays(7),  // exactly on the frequency boundary
            'reputation'      => 200,
        ]);

        $command = new ApplyReputationDecay();
        $command->applyDecay($user);

        // 200 * 0.01 = 2 points deducted
        expect($user->fresh()->reputation)->toBe(198);
    });

    it('enforces a minimum penalty of 1 point', function (): void {
        $user = User::factory()->create([
            'last_seen_at'    => now()->subDays(20), // 1% tier
            'last_decayed_at' => now()->subDays(7),
            'reputation'      => 1, // floor(1 * 0.01) = 0, should be bumped to 1
        ]);

        $command = new ApplyReputationDecay();
        $command->applyDecay($user);

        expect($user->fresh()->reputation)->toBe(0);
    });

    it('treats a never-decayed user as immediately eligible', function (): void {
        $user = User::factory()->create([
            'last_seen_at'    => now()->subDays(20), // 1% / 7-day tier, 20 days inactive
            'last_decayed_at' => null,               // falls back to daysInactive (20 >= 7)
            'reputation'      => 100,
        ]);

        $command = new ApplyReputationDecay();
        $command->applyDecay($user);

        expect($user->fresh()->reputation)->toBe(99);
    });

    it('applies the correct penalty for each tier', function (int $daysInactive, int $reputation, int $expected): void {
        $user = User::factory()->create([
            'last_seen_at'    => now()->subDays($daysInactive),
            'last_decayed_at' => now()->subDays(30), // always past any frequency threshold
            'reputation'      => $reputation,
        ]);

        $command = new ApplyReputationDecay();
        $command->applyDecay($user);

        expect($user->fresh()->reputation)->toBe($expected);
    })->with([
        '1% tier'  => [20,  1000, 990], // 1000 * 0.01 = 10
        '3% tier'  => [45,  1000, 970], // 1000 * 0.03 = 30
        '5% tier'  => [90,  1000, 950], // 1000 * 0.05 = 50
    ]);
});

describe('handle', function (): void {
    it('outputs a success message after running', function (): void {
        $this->artisan('vl-wbk:apply-reputation-decay')
            ->expectsOutput('Reputation decay applied.')
            ->assertExitCode(0);
    });

    it('does not touch users within the grace period', function (): void {
        $user = User::factory()->create([
            'last_seen_at' => now()->subDays(10),
            'reputation'   => 100,
        ]);

        $this->artisan('vl-wbk:apply-reputation-decay');

        expect($user->fresh()->reputation)->toBe(100);
    });

    it('does not touch users with zero reputation', function (): void {
        $user = User::factory()->create([
            'last_seen_at' => now()->subDays(30),
            'reputation'   => 0,
        ]);

        $this->artisan('vl-wbk:apply-reputation-decay');

        expect($user->fresh()->reputation)->toBe(0);
    });

    it('decays all eligible users', function (): void {
        $eligible = User::factory()->count(3)->create([
            'last_seen_at'    => now()->subDays(20),
            'last_decayed_at' => null,
            'reputation'      => 100,
        ]);

        $exempt = User::factory()->create([
            'last_seen_at' => now()->subDays(5),
            'reputation'   => 100,
        ]);

        $this->artisan('vl-wbk:apply-reputation-decay');

        $eligible->each(fn ($u) => expect($u->fresh()->reputation)->toBe(99));
        expect($exempt->fresh()->reputation)->toBe(100);
    });
});
