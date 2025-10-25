<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('part_of_speeches', function (Blueprint $table): void {
            $table->after('id', function (Blueprint $table): void {
                $table->boolean('suggestible')
                    ->default(true)
                    ->comment('Indicator that indicates whether the part of speech in usable in the suggestion form');
            });
        });
    }
};
