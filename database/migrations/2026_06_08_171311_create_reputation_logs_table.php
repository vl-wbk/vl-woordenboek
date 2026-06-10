<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->after('id', function () use ($table): void {
                $table->integer('reputation')->default(0);
                $table->timestamp('last_decayed_at')->nullable();
            });
        });

        Schema::create('reputation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points'); // Can be positive or negative
            $table->string('reason'); // e.g., 'submission_approved', 'submission_invalidated'
            $table->timestamps();
        });
    }
};
