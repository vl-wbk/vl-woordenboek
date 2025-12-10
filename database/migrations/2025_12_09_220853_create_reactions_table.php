<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Article;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reactions', static function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Article::class)->constrained()->cascadeOnDelete();
            $table->smallInteger('insight_category')->index();
            $table->string('author');
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });
    }
};
