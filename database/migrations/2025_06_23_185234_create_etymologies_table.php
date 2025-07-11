<?php

use App\Models\Article;
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
        Schema::create('etymologies', function (Blueprint $table) {
            $table->id()->comment('De uniek ID');

            $table->smallInteger('status')->comment('De status van de etymologie entry in de databank.');
            $table->smallInteger('type')->nullable()->comment('Ontlening, erfwoord, neologisme, ...');

            $table->foreignIdFor(Article::class)->nullable()->comment('Relationele koppeling naar het lemma in het woordenboek')->constrained()->cascadeOnDelete();

            $table->string('origin_language')->comment('Taal van oorspong (vb.latijn)');
            $table->string('origin_form')->comment('Vorm in de brontaal (vb. Hospitale)');
            $table->string('source')->nullable()->comment('Naam van de bron (bv. WNT, EWN)');
            $table->string('source_url')->comment('Link naar online bron (optioneel)');

            $table->text('note')->nullable()->comment('Extra toelichting of twijfelgevallen');
            $table->text('etymology')->nullable()->comment('beschrijving van de herkomst');

            $table->dateTime('period_start')->nullable()->comment('Begin van het gebruik (vb. "13e eeuw")');
            $table->dateTime('period_end')->nullable()->comment('Eind van het gebruik (vb. "19e eeuw" of "heden") ');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }
};
