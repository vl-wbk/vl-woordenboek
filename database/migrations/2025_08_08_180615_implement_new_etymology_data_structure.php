<?php

use App\Models\{Article, User};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Remove old tables because we are gonne use a total new structure.
        Schema::dropIfExists('etymologies');

        // 2) Implement the new database table layout.
        Schema::create('etymologies', function (Blueprint $table): void {
            $table->id()->comment('Unique identifier for each etymology entry');
            $table->text('etymology')->nullable()->comment('The word or term whose etymology is being described (based on the etymologie key in the suggestion form)');
            $table->string('origin')->nullable()->comment('The origin of the word (based on the oorsprong key in the suggestion form)');
            $table->string('origin_period')->nullable()->comment("The time period associated with the word's origin (based on the oorspong_periode key in the suggestion form)");
            $table->text('further_development')->nullable()->comment("Details on the word's later development (based on the verder_ontwikkeling key in the suggestion form)");
            $table->string('further_development_period')->nullable()->comment("The time period for the further development (based on the verdere_ontwikkeling_periode key in the suggestion form)");
            $table->string('oldest_find_spot')->nullable()->comment('The oldest known location where the word was found (based on the oudste_vindplaats key in the suggestion form.)');
            $table->integer('oldest_find_period')->nullable()->comment("The time period of the oldest find (based on the oude_vindplaats_periode key in the suggestion form)");
            $table->text('additional_info')->nullable()->comment('Any supplemental or additional information (from aanvullingen key in the suggestion form');
            $table->unsignedInteger('source_name')->comment('The name of the source (from bron_naam in the siggestion form)');
            $table->string('source_hyperlink')->nullable()->comment('A hyperlink to the source (from bron_hyperlink on the suggestion form)');

            // Meta and relation fields.
            $table->smallInteger('status')->comment('The status of the eytmology entry in the database')->index();
            $table->foreignIdFor(Article::class)->nullable()->comment('A relational link to the lemma in the dictionary (articles table)')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'author_id')->nullable()->comment('The user who authored the entry (users table)')->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'rejected_by')->nullable()->comment('The user who rejected the entry (users table)')->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'archived_by')->nullable()->comment('The user who archived the entry (users table).')->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'published_by')->nullable()->comment('The user who published the entry (users table)')->constrained()->nullOnDelete();
            $table->text('archiving_reason')->nullable()->comment('The reason for archivin the entry.');
            $table->text('rejection_reason')->nullable()->comment('The reason for rejecting the entry.');
            $table->timestamp('published_at')->nullable()->comment('The timestamp when teh entry was published');
            $table->timestamp('archived_at')->nullable()->comment('The timestamp when the entry was archived.');
            $table->timestamp('rejected_at')->nullable()->comment('The timestamp when the entry was rejected.');
            $table->timestamps();
        });
    }
};
