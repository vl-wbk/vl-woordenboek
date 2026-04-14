<?php

use App\Models\Article;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->json('migration_configuration')->nullable()->default(null);
        });

        Schema::table('user_examples', function (Blueprint $table): void {
            $table->after('example', function () use ($table): void {
                $table->string('source');
           });
        });

        Schema::table('concepts', function (Blueprint $table) {
            $table->dropColumn('example');
        });

        Article::chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $article->update([
                    'migration_configuration' => [
                        'examples' => false,
                    ]
                ]);
            }
        });
    }
};
