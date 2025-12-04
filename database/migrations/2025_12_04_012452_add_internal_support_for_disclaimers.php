<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disclaimers', static function (Blueprint $table): void {
            $table->after('description', static function (Blueprint $table): void {
                $table->string('internal_title')->nullable()->comment('De titel van de disclaimer voor interne weergave');
                $table->string('internal_message')->nullable()->comment('Het intern bericht aan de redacteurs voor de disclaimer');
            });
        });
    }
};
