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
            $table
                ->comment('This table is used for managing a structured word index in the application. Each record represents a word entry with associated metadata, including grammatical category, authorship, and descriptive information.');
            $table
                ->id()
                ->comment('Primary key, uniquely identifying each word entry.');
            $table
                ->foreignIdFor(PartOfSpeech::class)
                ->comment('Foreign key referencing the grammatical category of the word.')
                ->nullable()
                ->references('id')
                ->on('part_of_speeches')
                ->nullOnDelete();
            $table
                ->foreignIdFor(User::class, 'author_id')
                ->comment('Foreign key referencing the user who orginally added the word.')
                ->nullable()
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table
                ->foreignIdFor(User::class, 'editor_id')
                ->comment('Foreign key referencing the last editor of the word.')
                ->nullable()->references('id')
                ->on('users')
                ->nullOnDelete();
            $table
                ->string('index', 1)
                ->virtualAs("UPPER(LEFT(word, 1))")
                ->comment('A virtual column used for indexing words in the application.');
            $table
                ->smallInteger('state')
                ->default(ArticleStates::New->value)
                ->comment('A small integer representing the state of the dictionary article entry. Defaults to suggestion');
            $table
                ->string('word')
                ->comment('The actual word being index');
            $table
                ->smallInteger('status')
                ->default(LanguageStatus::Onbekend->value)
                ->comment('Represents the status of the word entry, default to 4 (unknown)');
            $table
                ->text('description')
                ->comment('A text field containing an  explanation or definition of the word')
                ->nullable();
            $table
                ->string('keywords')
                ->comment('A set of keywords associated with the word entry.')
                ->nullable();
            $table
                ->text('example')
                ->comment('A text field providing example usage of the word')
                ->nullable();
            $table
                ->text('characteristics')
                ->comment('Additional textual characteristics or attributes to the word')
                ->nullable();
            $table
                ->timestamp('created_at')
                ->nullable()
                ->comment('Timestamp indicating when the entry was created');
            $table
                ->timestamp('updated_at')
                ->nullable()
                ->comment('Timestamp indicating the last update to the entry.');
        });

        Schema::create('article_region', function (Blueprint $table) {
            $table
                ->comment('Links articles to specific regions, allowing for region-based categorization or filtering of content.');
            $table
                ->id()
                ->comment('Primary key for the table');
            $table
                ->foreignIdFor(Region::class)
                ->comment('Foreign key referencing a specific region.')
                ->references('id')
                ->on('regions')
                ->cascadeOnDelete();
            $table
                ->foreignIdFor(Article::class)
                ->comment('Foreign key referencing a specific article.')
                ->references('id')
                ->on('articles')
                ->cascadeOnDelete();
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
