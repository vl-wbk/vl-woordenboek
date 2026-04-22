<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE articles ADD FULLTEXT INDEX ft_word_keywords (word, keywords)');
        DB::statement('ALTER TABLE articles ADD FULLTEXT INDEX ft_word_keywords_description (word, keywords, description)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE articles DROP INDEX ft_word_keywords');
        DB::statement('ALTER TABLE articles DROP INDEX ft_word_keywords_description');
    }
};
