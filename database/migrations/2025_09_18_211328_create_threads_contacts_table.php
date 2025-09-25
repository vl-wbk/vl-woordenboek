<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('threads_contacts', function (Blueprint $table): void {
            $table->id();
			$table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
			$table->foreignIdFor(User::class, 'contact_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
};
