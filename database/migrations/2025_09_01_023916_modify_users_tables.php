<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
			$table->dropColumn(columns: ['name']);
			
			$table->string('name');
			$table->string('firstname')->nullable()->change();
			$table->string('lastname')->nullable()->change();
		});
    }
};
