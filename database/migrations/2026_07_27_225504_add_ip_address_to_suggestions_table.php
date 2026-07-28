<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', static function (Blueprint $table): void {
            $table->string('ip_address', 45)->nullable()->after('author_id');

            // Quick lookup for quota management (anonymous + logged in)
            $table->index(['ip_address', 'created_at']);
            $table->index(['author_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('articles', static function (Blueprint $table): void {
            $table->dropIndex(['ip_address', 'created_at']);
            $table->dropIndex(['author_id', 'created_at']);
            $table->dropColumn('ip_address');
        });
    }
};
