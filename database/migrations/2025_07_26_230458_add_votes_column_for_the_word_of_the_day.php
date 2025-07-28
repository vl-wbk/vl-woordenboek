<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            $table->after('views', function (Blueprint $table): void {
                $table->unsignedBigInteger('votes_today')->default(0)->comment('The column that tracks how many times the word is liked today. +1 = like & -1 = dislike');
                $table->boolean('wotd')->default(false)->comment('Indicates that the article is the word of the day');
            });
        });
    }
};
