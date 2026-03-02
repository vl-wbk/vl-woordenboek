<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('labels', function (Blueprint $table) {
            $table->after('id', function () use ($table): void {
                $table->boolean('private')->default(false);
            });
        });
    }
};
