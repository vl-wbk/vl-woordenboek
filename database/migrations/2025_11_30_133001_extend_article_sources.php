<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reference_works', static function (Blueprint $table): void {
            $table->after('id', static function (Blueprint $table): void {
                $table->string('type');
                $table->string('publisher');
            });

            $table->after('name', static function (Blueprint $table): void {
                $table->dateTime('published_at')->nullable();
            });
        });

        Schema::table('article_sources', function (Blueprint $table) {
            $table->renameColumn('notation', 'container_section');
            $table->string('page_reference')->nullable();
        });
    }
};
