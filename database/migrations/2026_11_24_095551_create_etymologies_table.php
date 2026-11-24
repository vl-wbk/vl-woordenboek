<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('etymologies');


        Schema::create('etymologies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Article::class)->unique()->constrained()->cascadeOnDelete(); // 1 per woord
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->text('origin');
            $table->string('source_language', 10)->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });

        Schema::table('etymologies', function (Blueprint $table) {
            $table->string('status')->default('pending')->index();
            $table->unsignedBigInteger('published_article_id')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
        });

        // Bestaande etymologieën zijn al publiek, dus meteen goedgekeurd.
        DB::table('etymologies')->update([
            'status'               => 'approved',
            'published_article_id' => DB::raw('article_id'),
        ]);

        Schema::table('etymologies', function (Blueprint $table) {
            // Alleen gevuld bij 'approved': garandeert max. 1 gepubliceerde per woord.
            // NULL-waarden mogen meerdere keren voorkomen in een unique index.
            $table->unique('published_article_id');
            $table->foreign('published_article_id')->references('id')->on('articles')->nullOnDelete();

            // De oude unieke index op article_id moet weg, anders blokkeert
            // één pending voorstel het hele woord. Eerst een gewone index
            // toevoegen omdat de foreign key er een nodig heeft (MySQL).
            $table->index('article_id', 'etymologies_article_id_index');
            $table->dropUnique(['article_id']); // pas aan als je index anders heet
        });
    }

    public function down(): void
    {
        // Bewust leeg: terug naar 1 rij per woord kan data verliezen.
    }
};
