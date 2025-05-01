<?php

use App\Enums\ArticleStates;
use App\Enums\DataOrigin;
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
            $table->unsignedSmallInteger('origin')->default(DataOrigin::External->value);
            $table->smallInteger('state')->default(ArticleStates::ExternalData->value);
            $table->foreignIdFor(PartOfSpeech::class)->nullable()->references('id')->on('part_of_speeches')->nullOnDelete();
            $table->foreignIdFor(User::class, 'author_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignIdFor(User::class, 'editor_id')->nullable()->references('id')->on('users')->nullOnDelete();

            $table->foreignIdFor(User::class, 'publisher_id')
                ->nullable()
                ->comment('The person who approved the article for publishing it into the dictionary')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreignIdFor(User::class, 'archiever_id')->nullable()->constrained();
            $table->string('word')->fulltext();
            $table->integer('views')->default('0');
            $table->smallInteger('status')->default(LanguageStatus::Onbekend->value);
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->string('keywords')->nullable()->fulltext();
            $table->text('example')->nullable();
            $table->text('characteristics')->nullable();
            $table->string('archiving_reason', 350)->nullable();
            $table->json('sources')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamp('published_at')->nullable();
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
