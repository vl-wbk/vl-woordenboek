<?php

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
        Schema::table('articles', function (Blueprint $table) {
            $table->after('disclaimer_id', function (Blueprint $table): void {
                $table->foreignIdFor(User::class, 'deleted_by')->nullable()->constrained()->nullOnDelete();
                $table->text('deletion_reason')->nullable();
            });
        });
    }
};
