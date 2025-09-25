<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
			$table->after('email', function (Blueprint $table): void {
				$table->string('bio', 160)->nullable();
				$table->string('twitter')->nullable();
				$table->string('bluesky')->nullable();
				$table->string('website')->nullable();
			});
		});
    }
};
