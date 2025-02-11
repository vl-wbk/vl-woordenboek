<?php

use App\Models\Region;
use App\Models\Word;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->string('word');
            $table->string('description');
            $table->text('example');
            $table->text('characteristics');
            $table->timestamps();
        });

        Schema::create('region_word', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Region::class)->references('id')->on('regions')->cascadeOnDelete();
            $table->foreignIdFor(Word::class)->references('id')->on('words')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('region_word');
        Schema::dropIfExists('words');
    }
};
