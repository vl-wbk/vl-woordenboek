<?php

use App\Models\Article;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_examples', function (Blueprint $table): void {
            $table->dropForeign(['article_id']);
            $table->string('exampleable_type')->nullable()->after('status');
            $table->renameColumn('article_id', 'exampleable_id');
            $table->index(['exampleable_type', 'exampleable_id']);
            $table->softDeletes();
        });

        DB::table('user_examples')->update(['exampleable_type' => Article::class]);

        Schema::table('user_examples', function (Blueprint $table): void {
            $table->string('exampleable_type')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('user_examples', function (Blueprint $table): void {
            if (Schema::hasIndex('user_examples', 'user_examples_exampleable_type_exampleable_id_index')) {
                $table->dropIndex(['exampleable_type', 'exampleable_id']);
            }
        });

        Schema::table('user_examples', function (Blueprint $table): void {
            if (Schema::hasColumn('user_examples', 'exampleable_type')) {
                $table->dropColumn('exampleable_type');
            }
        });

        if (Schema::hasColumn('user_examples', 'exampleable_id') && ! Schema::hasColumn('user_examples', 'article_id')) {
            Schema::table('user_examples', function (Blueprint $table): void {
                $table->renameColumn('exampleable_id', 'article_id');
            });
        }

        Schema::table('user_examples', function (Blueprint $table): void {
            if (Schema::hasColumn('user_examples', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('user_examples', function (Blueprint $table): void {
            $table->foreign('article_id')->references('id')->on('articles');
        });
    }
};
