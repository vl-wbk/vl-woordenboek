<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_state_transitions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->smallInteger('from_state')->nullable();   // null = article creation
            $table->smallInteger('to_state');
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason', 350)->nullable();
            $table->json('context')->nullable();              // ip, source, via
            $table->timestamp('created_at')->useCurrent();

            // article_id is already indexed by the foreign key.
            $table->index(['to_state', 'created_at']);
        });

        DB::table('article_state_transitions')->insertUsing(
            ['article_id', 'from_state', 'to_state', 'reason', 'context', 'created_at'],
            DB::table('articles')->select([
                'id',
                DB::raw('NULL'),
                'state',
                DB::raw("'Baseline: state when transition logging was introduced'"),
                DB::raw("JSON_OBJECT('source', 'backfill')"),
                DB::raw('COALESCE(updated_at, created_at, NOW())'),
            ]),
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('article_state_transitions');
    }
};
