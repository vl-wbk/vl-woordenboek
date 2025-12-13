<?php

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
        Schema::create('moderation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('pattern'); // string of regex
            $table->string('category'); // racisme / archaïsch / politiek / dubbelzinnig
            $table->text('explanation')->nullable();
            $table->text('neutral_alternative')->nullable();
            $table->boolean('is_regex')->default(false);

            // context analyseren:
            $table->json('allowed_contexts')->nullable(); // woorden waarbij flagged NIET geldt
            $table->json('forbidden_contexts')->nullable(); // woorden waarbij flagged WEL geldt
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderation_rules');
    }
};
