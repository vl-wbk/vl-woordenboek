<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('preferables', function (Blueprint $table) {
            $table->index(['preferable_type', 'preferable_id', 'preferences_id'], 'idx_preferables_covering');
        });
    }
};
