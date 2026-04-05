<?php

use App\Models\Concept;
use App\Models\PartOfSpeech;
use App\Models\Region;
use App\Models\User;
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
        Schema::create('concepts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'author_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(PartOfSpeech::class)->nullable()->constrained()->nullOnDelete();
            $table->string('word')->nullable()->index();
            $table->string('characteristics')->nullable();
            $table->text('description')->nullable();
            $table->text('example')->nullable();
            $table->boolean('notify_author');
            $table->timestamps();
        });

        Schema::create('concept_region', function (Blueprint $table): void {
            $table->foreignIdFor(Concept::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Region::class)->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concept_region');
        Schema::dropIfExists('concepts');
    }
};
