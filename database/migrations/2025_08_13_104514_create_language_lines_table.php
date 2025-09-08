<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('language_lines', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('group')->index();
            $table->string('key')->index();
            $table->json('text')->default(new Expression('(JSON_ARRAY())'));
            $table->timestamps();
        });
    }
};
