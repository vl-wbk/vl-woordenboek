<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reference_works', function (Blueprint $table) {
            $table->after('name', function () use ($table) {
                $table->string('external_url')->nullable()->unique();
            });
        });
    }
};
