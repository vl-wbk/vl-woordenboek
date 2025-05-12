<?php

use App\Models\Disclaimer;
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
        Schema::create('disclaimers', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('type');
            $table->string('name');
            $table->text('message');
            $table->text('usage');
            $table->text('description');
            $table->timestamps();
        });

        Schema::table('articles', function (Blueprint $table): void {
            $table->foreignIdFor(Disclaimer::class)->after('sources')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            $table->dropConstrainedForeignIdFor(Disclaimer::class);
        });

        Schema::dropIfExists('disclaimers');
    }
};
