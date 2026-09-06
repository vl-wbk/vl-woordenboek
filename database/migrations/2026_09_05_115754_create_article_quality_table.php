<?php

use App\Models\Article;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_quality', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Article::class)->unique()->constrained()->cascadeOnDelete();

            $table->unsignedInteger('upvotes');
            $table->unsignedInteger('downvotes');

            $table->unsignedTinyInteger('score')->default(50);
            $table->unsignedTinyInteger('confidence')->default(0);
            $table->unsignedTinyInteger('controversy')->default(0);
            $table->unsignedTinyInteger('review_priority')->default(0);

            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();

            $table->index('review_priority');
            $table->index('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_quality');
    }
};
