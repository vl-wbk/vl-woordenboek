<?php

use App\Models\PartOfSpeech;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('correction_proposals', function (Blueprint $table): void {
            $table->after('article_id', function () use ($table): void {
                $table->foreignIdFor(PartOfSpeech::class)->nullable()->constrained()->nullOnDelete();
            });

            $table->after('moderator_id', function () use ($table): void {
                $table->string('characteristics')->nullable();
            });
        });
    }
};
