<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('etymologies', function (Blueprint $table): void {
            $table->id()->comment('De uniek ID');

            $table->dateTime('period_start')->nullable()->comment('Begin van het gebruik (vb. "13e eeuw")');
            $table->dateTime('period_end')->nullable()->comment('Eind van het gebruik (vb. "19e eeuw" of "heden") ');

            $table->smallInteger('status')->comment('De status van de etymologie entry in de databank.')->index();
            $table->smallInteger('type')->nullable()->comment('Ontlening, erfwoord, neologisme, ...')->index();

            $table->foreignIdFor(Article::class)->nullable()->comment('Relationele koppeling naar het lemma in het woordenboek')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'author_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'rejected_by')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'archived_by')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'published_by')->nullable()->constrained()->nullOnDelete();

            $table->string('origin_language')->comment('Taal van oorspong (vb.latijn)');
            $table->string('origin_form')->comment('Vorm in de brontaal (vb. Hospitale)');
            $table->string('source')->nullable()->comment('Naam van de bron (bv. WNT, EWN)');
            $table->string('source_url')->nullable()->comment('Link naar online bron (optioneel)');

            $table->text('note')->nullable()->comment('Extra toelichting of twijfelgevallen');
            $table->text('etymology')->nullable()->comment('beschrijving van de herkomst');

            $table->text('archiving_reason')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamp('published_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();
        });
    }
};
