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
            $table->foreignUlid('category_id')->nullable()->constrained()->nullOnDelete();
            $table->smallInteger('status')->nullable();
            $table->string('title');
            $table->string('content');
            $table->integer('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('categories');
    }
};
