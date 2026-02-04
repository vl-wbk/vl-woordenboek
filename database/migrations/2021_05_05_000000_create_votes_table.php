<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(config('vote.votes_table'), static function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedBigInteger(config('vote.user_foreign_key'))->index()->comment('user_id');
            $table->integer('votes');
            $table->morphs('votable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('vote.votes_table'));
    }
}
