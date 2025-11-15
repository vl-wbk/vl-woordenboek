<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

return new class extends Migration
{
    public function up(): void
    {
        $bookmarkFoldersTable = Config::string('page-bookmarks.tables.bookmark_folders', 'bookmark_folders');
        $bookmarksTable = Config::string('page-bookmarks.tables.bookmarks', 'bookmarks');

        Schema::create($bookmarkFoldersTable, static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create($bookmarksTable, static function (Blueprint $table) use ($bookmarkFoldersTable): void {
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
        Schema::dropIfExists(Config::string('page-bookmarks.tables.bookmarks', 'bookmarks'));
        Schema::dropIfExists(config('page-bookmarks.tables.bookmark_folders', 'bookmark_folders'));
    }
};
