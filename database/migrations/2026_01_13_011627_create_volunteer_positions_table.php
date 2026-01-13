<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('volunteer_positions', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_open')->default(false);
            $table->foreignIdFor(Role::class)->nullable()->constrained()->nullOnDelete();
            $table->tinyInteger('associated_user_group')->unsigned();
            $table->string('name');
            $table->string('tag_line')->nullable();
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_positions');
    }
};
