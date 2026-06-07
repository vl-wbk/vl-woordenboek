<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->after('user_type', fn () => $table->integer('trust_score')
                ->nullable()
                ->default('0')
                ->comment('The turst score of the user into the dictionary this is used as a confidentiality rating')
            );
        });
    }
};
