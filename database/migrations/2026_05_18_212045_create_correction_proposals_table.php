<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('correction_proposals', static function (Blueprint $table): void {
            $table->id()
                ->comment('The unique ideentifier (primary key) from the correction');
            $table->foreignIdFor(Article::class)
                ->comment('The unique identifier from the dictionary article')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'author_id')
                ->nullable()
                ->comment('The unique identifier from the user who submitted the correction')
                ->constrained()
                ->nullOnDelete();
            $table->foreignIdFor(User::class, 'moderator_id')
                ->comment('The unique ideentifier from the user who moderated the correction')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->text('description')
                ->comment('The suggested correction for the dictionary article description.'); 
            $table->text('reason')
                ->comment('The reason why the author wants this correction on the dictionary article.');
            $table->text('conclusion')
                ->comment('The conclusion from the moderator who moderated this correction proposal.')
                ->nullable();
            $table->timestamp('moderated_at')
                ->comment('The precise timestamp that indicate when the correction has been moderated.')
                ->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('correction_proposals');
    }
};
