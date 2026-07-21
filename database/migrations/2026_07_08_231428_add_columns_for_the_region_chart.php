<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            $table->after('example', function () use ($table): void {
                $table->string('region_chart')->nullable();
                $table->string('region_chart_source')->nullable();
            });
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            $table->dropColumn([
                'region_chart',
                'region_chart_source',
            ]);
        });
    }
};
