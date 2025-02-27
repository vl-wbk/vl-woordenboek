<?php

use App\Models\Article;
use App\Models\Label;
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
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('article_label', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Label::class)->constrained('labels', 'id')->cascadeOnDelete();
            $table->foreignIdFor(Article::class)->constrained('articles', 'id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labels');
        Schema::dropIfExists('article_label');
    }
};
