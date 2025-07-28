<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('article_region', function (Blueprint $table): void {
            $table->index(['region_id', 'article_id'], 'idx_article_region_region_article');
        });

        Schema::table('articles', function (Blueprint $table): void {
            $table->index(['deleted_at', 'published_at', 'views', 'id'], 'idx_articles_filters_order');
        });
    }
};
