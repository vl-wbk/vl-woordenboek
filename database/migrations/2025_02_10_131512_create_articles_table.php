<?php

use App\Enums\ArticleStates;
use App\Enums\LanguageStatus;
use App\Models\Region;
use App\Models\User;
use App\Models\Article;
use App\Models\PartOfSpeech;
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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('index', 1)
                ->comment('The index column is used in the word index of the application.')
                ->virtualAs("UPPER(LEFT(word, 1))");
            $table->smallInteger('state')->default(ArticleStates::New->value);
            $table->foreignIdFor(PartOfSpeech::class)->nullable()->references('id')->on('part_of_speeches')->nullOnDelete();
            $table->foreignIdFor(User::class, 'author_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignIdFor(User::class, 'editor_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->string('word');
            $table->smallInteger('status')->default(LanguageStatus::Onbekend->value);
            $table->text('description')->nullable();
            $table->string('keywords')->nullable();
            $table->text('example')->nullable();
            $table->text('characteristics')->nullable();
            $table->timestamps();
        });

        Schema::create('article_region', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Region::class)->references('id')->on('regions')->cascadeOnDelete();
            $table->foreignIdFor(Article::class)->references('id')->on('articles')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_region');
        Schema::dropIfExists('articles');
    }
};
