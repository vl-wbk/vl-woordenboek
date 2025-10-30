<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $bookmarkFoldersTable = config('page-bookmarks.tables.bookmark_folders', 'bookmark_folders');
        $bookmarksTable = config('page-bookmarks.tables.bookmarks', 'bookmarks');

        Schema::create($bookmarkFoldersTable, function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create($bookmarksTable, function (Blueprint $table) use ($bookmarkFoldersTable): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('bookmark_folder_id')->nullable()->constrained($bookmarkFoldersTable)->nullOnDelete();
            $table->text('url');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('page-bookmarks.tables.bookmarks', 'bookmarks'));
        Schema::dropIfExists(config('page-bookmarks.tables.bookmark_folders', 'bookmark_folders'));
    }
};
