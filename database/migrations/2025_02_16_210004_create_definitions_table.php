<?php

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
        Schema::create('definitions', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Word::class)->references('id')->on('words')->cascadeOnDelete();
            $table->foreignIdfor(User::class, 'creator_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignIdFor(User::class, 'editor_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->text('description');
            $table->text('example');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('definitions');
    }
};
