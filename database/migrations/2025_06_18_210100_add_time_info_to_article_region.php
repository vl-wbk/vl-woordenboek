<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('article_region', function (Blueprint $table) {
            $table->dateTime('created_at')->nullable()->after('article_id');
            $table->dateTime('updated_at')->nullable()->after('created_at');
        });
    }
};
