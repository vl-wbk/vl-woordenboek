<?php

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
        Schema::create('categories', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('blogs', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignIdFor(User::class, 'author_id')->nullable()->constrained()->nullOnDelete();
            $table->smallInteger('status')->nullable();
            $table->string('title');
            $table->text('content');
            $table->integer('views')->default(0);
            $table->boolean('comments_enabled')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('post_categories', function (Blueprint $table): void {
            $table->foreignUlid('blog_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_categories');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('categories');
    }
};
