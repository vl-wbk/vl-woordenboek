<?php 

declare(strict_types=1);

namespace Tests\Unit\Concerns;

use App\Concerns\RateLimitSubmission;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
 
// -----------------------------------------------------------------------------
// Test double
// -----------------------------------------------------------------------------
 
function makeConsumer(?string $profile = null): object
{
    return new class ($profile) {
        use RateLimitSubmission;
 
        public ?string $rateLimitProfile;
 
        public function __construct(?string $profile = null)
        {
            $this->rateLimitProfile = $profile;
        }
 
        public function run(Request $request, string $key, Closure $callback): mixed
        {
            return $this->throttleSubmission($request, $key, $callback);
        }
    };
}
 
function makeRequest(?User $user = null, string $ip = '127.0.0.1'): Request
{
    $request = Request::create('/test', 'POST');
    $request->server->set('REMOTE_ADDR', $ip);
 
    if ($user) {
        $request->setUserResolver(fn () => $user);
    }
 
    return $request;
}
 
function exhaust(string $cacheKey, int $times, int $decay = 300): void
{
    foreach (range(1, $times) as $i) {
        RateLimiter::hit($cacheKey, $decay);
    }
}
 
beforeEach(function () {
    RateLimiter::clear('test:127.0.0.1');
});
 
// -----------------------------------------------------------------------------
// throttleSubmission — happy path
// -----------------------------------------------------------------------------
 
describe('throttleSubmission()', function () {
    it('executes the callback and returns its value', function () {
        $result = makeConsumer()->run(makeRequest(), 'test', fn () => 'expected-value');
 
        expect($result)->toBe('expected-value');
    });
 
    it('increments the rate limiter after a successful callback', function () {
        makeConsumer()->run(makeRequest(), 'test', fn () => null);
 
        expect(RateLimiter::attempts('test:127.0.0.1'))->toBe(1);
    });
 
    it('does not increment the rate limiter when the limit is already exceeded', function () {
        exhaust('test:127.0.0.1', 4);
 
        try {
            makeConsumer()->run(makeRequest(), 'test', fn () => null);
        } catch (ValidationException) {
            // expected
        }
 
        expect(RateLimiter::attempts('test:127.0.0.1'))->toBe(4);
    });
});
 
// -----------------------------------------------------------------------------
// Rate limit enforcement
// -----------------------------------------------------------------------------
 
describe('rate limit enforcement', function () {
    it('throws a ValidationException when the guest limit of 4 is exceeded', function () {
        exhaust('test:127.0.0.1', 4);
 
        $thrown = false;
 
        try {
            makeConsumer()->run(makeRequest(), 'test', fn () => null);
        } catch (ValidationException) {
            $thrown = true;
        }
 
        expect($thrown)->toBeTrue();
    });
 
    it('includes a rate_limit error key in the ValidationException', function () {
        exhaust('test:127.0.0.1', 4);
 
        try {
            makeConsumer()->run(makeRequest(), 'test', fn () => null);
        } catch (ValidationException $e) {
            expect($e->errors())->toHaveKey('rate_limit');
            return;
        }
 
        fail('Expected a ValidationException to be thrown.');
    });
 
    it('allows exactly 4 guest attempts before blocking', function () {
        $consumer = makeConsumer();
        $request  = makeRequest();
 
        foreach (range(1, 4) as $i) {
            $consumer->run($request, 'test', fn () => null);
        }
 
        $thrown = false;
 
        try {
            $consumer->run($request, 'test', fn () => null);
        } catch (ValidationException) {
            $thrown = true;
        }
 
        expect($thrown)->toBeTrue();
    });
 
    it('applies the member limit of 12 for authenticated users', function () {
        $user = User::factory()->create();
        $key  = 'test:' . $user->getAuthIdentifier();
 
        $this->actingAs($user);
        RateLimiter::clear($key);
 
        $consumer = makeConsumer();
        $request  = makeRequest($user);
 
        foreach (range(1, 12) as $i) {
            $consumer->run($request, 'test', fn () => null);
        }
 
        $thrown = false;
 
        try {
            $consumer->run($request, 'test', fn () => null);
        } catch (ValidationException) {
            $thrown = true;
        }
 
        expect($thrown)->toBeTrue();
    });
 
    it('does not block authenticated users within the member limit of 12', function () {
        $user = User::factory()->create();
 
        $this->actingAs($user);
        RateLimiter::clear('test:' . $user->getAuthIdentifier());
 
        $request = makeRequest($user);
 
        $consumer = makeConsumer();
 
        foreach (range(1, 12) as $i) {
            $result = $consumer->run($request, 'test', fn () => 'ok');
            expect($result)->toBe('ok');
        }
    });
});
 
describe('cache key isolation', function () {
    it('uses the IP address as the identifier for guests', function () {
        RateLimiter::clear('test:10.0.0.1');
 
        makeConsumer()->run(makeRequest(ip: '10.0.0.1'), 'test', fn () => null);
 
        expect(RateLimiter::attempts('test:10.0.0.1'))->toBe(1);
        expect(RateLimiter::attempts('test:127.0.0.1'))->toBe(0);
    });
 
    it('uses the user ID as the identifier for authenticated users', function () {
        $user = User::factory()->create();
        $key  = 'test:' . $user->getAuthIdentifier();
 
        RateLimiter::clear($key);
 
        makeConsumer()->run(makeRequest($user, '10.0.0.1'), 'test', fn () => null);
 
        expect(RateLimiter::attempts($key))->toBe(1);
        expect(RateLimiter::attempts('test:10.0.0.1'))->toBe(0);
    });
 
    it('isolates attempts between different action keys', function () {
        RateLimiter::clear('other:127.0.0.1');
 
        makeConsumer()->run(makeRequest(), 'test', fn () => null);
 
        expect(RateLimiter::attempts('other:127.0.0.1'))->toBe(0);
    });
});
 
describe('rate limit profile resolution', function () {
    it('falls back to the default profile when rateLimitProfile is null', function () {
        $consumer = makeConsumer(null);
        $request  = makeRequest();
 
        foreach (range(1, 4) as $i) {
            $consumer->run($request, 'test', fn () => null);
        }
 
        $thrown = false;
 
        try {
            $consumer->run($request, 'test', fn () => null);
        } catch (ValidationException) {
            $thrown = true;
        }
 
        expect($thrown)->toBeTrue();
    });
 
    it('uses a custom profile when rateLimitProfile is set', function () {
        Config::set('flemish-dictionary.rate-limiting.strict', [
            'guest_limit'   => 1,
            'member_limit'  => 2,
            'decay_seconds' => 300,
        ]);
 
        RateLimiter::clear('test:127.0.0.1');
 
        $consumer = makeConsumer('strict');
        $request  = makeRequest();
 
        $consumer->run($request, 'test', fn () => null);
 
        $thrown = false;
 
        try {
            $consumer->run($request, 'test', fn () => null);
        } catch (ValidationException) {
            $thrown = true;
        }
 
        expect($thrown)->toBeTrue();
    });
});
 
 
describe('handleRateLimitFailure()', function () {
    it('includes the remaining seconds in the error message', function () {
        exhaust('test:127.0.0.1', 4);
 
        try {
            makeConsumer()->run(makeRequest(), 'test', fn () => null);
        } catch (ValidationException $e) {
            expect($e->errors()['rate_limit'][0])->toContain('seconds');
            return;
        }
 
        fail('Expected a ValidationException to be thrown.');
    });
});