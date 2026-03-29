<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->after('archiving_reason', function () use ($table): void {
                $table->unsignedBigInteger('redirect_article_id')
                    ->nullable()
                    ->comment('The unique identifier from the article that is given as alternative during the archiving of an existing article');
            });
        });
    }
};
