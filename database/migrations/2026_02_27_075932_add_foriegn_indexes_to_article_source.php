<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('article_sources', function (Blueprint $table) {
            $table->dropForeign('article_sources_article_id_foreign');
            $table->foreign('article_id')
                ->references('id')
                ->on('articles')
                ->onDelete('cascade');

            $table->dropForeign('article_sources_reference_work_id_foreign');
            $table->foreign('reference_work_id')->references('id')->on('reference_works')->onDelete('cascade');
        });
    }
};
