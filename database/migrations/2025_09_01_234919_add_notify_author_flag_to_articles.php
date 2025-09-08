<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            $table->after('state', function (Blueprint $table): void {
                $table->boolean('notify_author')->default(false);
            });
        });
    }
};
