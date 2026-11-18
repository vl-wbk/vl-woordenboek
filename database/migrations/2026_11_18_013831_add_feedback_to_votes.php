<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('votes', function (Blueprint $table) {
        $table->text('feedback')->nullable()->after('votable_id');
    });
}

public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn('feedback');
        });
    }
};
