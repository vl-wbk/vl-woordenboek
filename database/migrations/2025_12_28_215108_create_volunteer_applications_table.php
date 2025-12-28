<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_applications', static function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(User::class, 'volunteer_id')->constrained()->cascadeOnDelete();
            $table->smallInteger('role');
            $table->text('motivation');
            $table->text('background');
            $table->timestamps();
        });
    }

    /** @todo remove this function when the feature is ready for production */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_applications');
    }
};
