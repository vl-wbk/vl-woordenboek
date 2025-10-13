<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authentication_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete();
            $table->smallInteger('event');
            $table->string('guard')->nullable();
            $table->string('message')->nullable();
            $table->ipAddress();
            $table->text('user_agent');
            $table->json('context')->nullable();
            $table->timestamps();
        });
    }
};
