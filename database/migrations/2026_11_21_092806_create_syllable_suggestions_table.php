<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->json('syllables')->nullable();                   // ["sjot","ten"]
            $table->unsignedTinyInteger('stress_index')->nullable(); // 1 = tweede lettergreep
        });

        // voorstellen van bezoekers
        Schema::create('syllable_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('syllables');
            $table->unsignedTinyInteger('stress_index');
            $table->string('signature');                  // bv. "sjot-ten|1", om gelijke voorstellen te tellen
            $table->string('status')->default('pending'); // of jouw state-klasse
            $table->boolean('auto_approved')->default(false);
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();

            $table->unique(['article_id', 'user_id']);    // één voorstel per gebruiker per woord
            $table->index(['article_id', 'signature']);
        });
    }

    public function down(): void
    {
        // Omgekeerde volgorde van up(): eerst de tabel met de foreign keys naar articles
        Schema::dropIfExists('syllable_suggestions');

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['syllables', 'stress_index']);
        });
    }
};
