<?php

use App\Models\Article;
use App\Models\Source;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            $table->dropColumn('sources');
        });

        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('abbreviation');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('article_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Source::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Article::class)->constrained()->cascadeOnDelete();
            $table->text('reference')->nullable();
            $table->timestamps();
        });
    }
};
