<?php

use App\Models\Article;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->json('migration_configuration');
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
