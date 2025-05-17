<?php

use App\Models\Region;
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
        Schema::create('region_geo_data', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Region::class)->nullable()->constrained()->nullOnDelete();
            $table->string('postal');
            $table->name('name');
            $table->geometry('geometry', 4326);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('region_geo_data');
    }
};
