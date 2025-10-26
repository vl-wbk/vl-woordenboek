<?php

use App\Models\Preferences;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->string('section');
            $table->string('preference')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('preferables', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Preferences::class)->constrained()->cascadeOnDelete();
            $table->morphs('preferable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preferables');
        Schema::dropIfExists('preferences');
    }
};
