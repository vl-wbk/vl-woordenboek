<?php

use App\Models\Article;
use App\Models\User;
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
        Schema::create('article_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Article::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'reviewed_by')->constrained('users')->restrictOnDelete();

            // The exact community-vote state that was reviewed
            $$table->unsignedBigInteger('votes_version');

            // Optional snapshot of the quality state at review time.
            // This makes historical reporting possible even if
            // the quality calculation changes later.
            $table->unsignedBigInteger('upvotes');
            $table->unsignedBigInteger('downvotes');

            $table->unsignedTinyInteger('score')->nullable();
            $table->unsignedTinyInteger('confidence')->default(0);
            $table->unsignedTinyInteger('controversy')->default(0);
            $table->unsignedTinyInteger('review_priority')->default(0);

            // What the editor decided
            $table->string('decision', 50);
            $table->text('notes');
            $table->timestamps();

            // Indexes
            $table->index(['article_id', 'votes_version']);
            $table->index(['article_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_reviews');
    }
};
