<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Article;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('related_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Article::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Article::class, 'related_article_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
};
