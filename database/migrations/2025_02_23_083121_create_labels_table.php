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
            $table
                ->comment('Stores labels with optional descriptions and timestamps for tracking creation and updates.');
            $table
                ->id()
                ->comment('Primary key for the label.');
            $table
                ->string('name')
                ->comment('The unique name of the label.')
                ->unique();
            $table
                ->text('description')
                ->comment('Optional description providing details about the label.')
                ->nullable();
            $table
                ->timestamp('created_at')
                ->nullable()
                ->comment('Timestamp indicating when the label was created.');
            $table
                ->timestamp('updated_at')
                ->nullable()
                ->comment('Timestamp indicating the last update to the label.');
        });

        Schema::create('article_label', function (Blueprint $table): void {
            $table
                ->comment('Links labels to articles, allowing articles to have multiple labels for categorization.');
            $table
                ->id()
                ->comment('Primary key for the label-article relationship.');
            $table
                ->foreignIdFor(Label::class)
                ->comment('Foreign key referencing the associated label.')
                ->constrained('labels', 'id')
                ->cascadeOnDelete();
            $table
                ->foreignIdFor(Article::class)
                ->comment('Foreign key referencing the associated article.')
                ->constrained('articles', 'id')
                ->cascadeOnDelete();
            $table
                ->timestamp('created_at')
                ->nullable()
                ->comment('Timestamp indicating when the relationship was created.');
            $table
                ->timestamp('updated_at')
                ->nullable()
                ->comment('Timestamp indicating the last update to the relationship.');
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
