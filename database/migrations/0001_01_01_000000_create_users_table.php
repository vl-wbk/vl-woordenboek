<?php

use App\UserTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('user_type');
            $table->string('name')->virtualAs("CONCAT(firstname, ' ', lastname)");
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('banned_at')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table
                ->comment('Stores user session data, including user details, IP address, user agent, and activity log information.');
            $table
                ->string('id')
                ->comment('Unique session identifier.')
                ->primary();
            $table
                ->foreignId('user_id')
                ->comment('Foreign key referencing the user associated with the session.')
                ->nullable()
                ->index();
            $table
                ->string('ip_address', 45)
                ->comment('IP address from which the session originated.')
                ->nullable();
            $table
                ->text('user_agent')
                ->comment('User agent string identifying the client or browser used in the session.')
                ->nullable();
            $table
                ->longText('payload')
                ->comment('Additional session data (e.g., actions, events, or metadata).');
            $table
                ->integer('last_activity')
                ->index()
                ->comment('Timestamp (in UNIX format) indicating the last activity in the session.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
