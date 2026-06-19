<?php

use App\Models\Article;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_examples', function (Blueprint $table): void {
            $table->dropForeign(['article_id']);

            $table->after('status', function() use($table): void {
                $table->string('exampleable_type')->nullable();
            });

            $table->renameColumn('article_id', 'exampleable_id');
            $table->index(['exampleable_type', 'exampleable_id']);
            $table->softDeletes();
        });

        DB::table('user_examples')->update([
            'exampleable_type' => Article::class,
        ]);
    }

    public function down(): void
    {
        Schema::table('user_examples', function (Blueprint $table): void {
            $table->dropColumn('exampleable_type');
            $table->renameColumn('exampleable_id', 'article_id');
            $table->dropSoftDeletes();
        });
    }
};
