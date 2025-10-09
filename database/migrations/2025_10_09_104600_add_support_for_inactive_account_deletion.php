<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('inactivity_warning_sent_at')->nullable();
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->string('contributor_name')->nullable();
        });
    }
};
