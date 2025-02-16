<?php

use App\Models\Region;
use App\Models\User;
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
            $table->string('index', 1)
                ->comment('The index column is used in the word index of the application.')
                ->virtualAs("UPPER(LEFT(word, 1))");
            $table->foreignIdFor(User::class, 'author_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->string('word');
            $table->smallInteger('status');
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
