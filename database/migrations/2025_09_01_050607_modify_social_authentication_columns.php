<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['provider', 'provider_id']);

            $table->after('is_beta_tester', function (Blueprint $table): void {
                $table->text('google_id')->nullable();
                $table->text('google_token')->nullable();
                $table->text('google_refresh_token')->nullable();
            });
        });
    }
};
