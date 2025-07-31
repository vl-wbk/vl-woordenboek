<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table): void {
            $table->after('id', function (Blueprint $table): void {
                $table->string('tracking_number')->unique();
            });
        });
    }
};
