<?php

use App\Models\Article;
use App\Models\ReferenceWork;
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

        Schema::create('reference_works', function (Blueprint $table): void {
            $table->id();
            $table->string('abbreviation')->nullable()->unique();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('article_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ReferenceWork::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Article::class)->constrained()->cascadeOnDelete();
            $table->text('notation')->nullable();
            $table->timestamps();
        });
    }
};
