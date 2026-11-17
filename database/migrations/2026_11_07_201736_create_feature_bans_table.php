<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_bans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('feature'); 
            $table->text('reason');
            $table->foreignIdFor(User::class, 'banned_by')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('expires_at')->nullable(); // null = permanent
            $table->timestamps();

            $table->unique(['user_id', 'feature']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_bans');
    }
};
