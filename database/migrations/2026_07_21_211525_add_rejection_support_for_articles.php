<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles',  static function (Blueprint $table): void {
            $table->after('archiever_id', function () use ($table): void {
                $table->foreignIdFor(User::class, 'rejected_by')->nullable()->constrained()->nullOnDelete();
            });

            $table->after('archiving_reason', function () use ($table): void {
                $table->string('rejection_reason')->nullable();
            });
        });
    }
};
