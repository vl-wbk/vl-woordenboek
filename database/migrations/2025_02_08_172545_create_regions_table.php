<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table
                ->comment('Stores geographical or administrative regions for categorization and reference.');
            $table
                ->id()
                ->comment('Primary key for the region.');
            $table
                ->string('name')
                ->comment('Name of the region');
            $table
                ->timestamp('created_at')
                ->nullable()
                ->comment('Timestamp indicating when the region was created.');
            $table
                ->timestamp('updated_at')
                ->nullable()
                ->comment('Timestamp indicating the last update to the region.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
