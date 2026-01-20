<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('word_of_the_days', static function (Blueprint $table): void {
            $table->text('scheduling_reason')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('word_of_the_days', static function (Blueprint $table): void {
            $table->text('scheduling_reason')->nullable(false)->change();
        });
    }
};
