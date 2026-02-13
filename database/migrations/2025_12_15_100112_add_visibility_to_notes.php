<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Note;
use App\Enums\Notes\Visibility;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', static function (Blueprint $table): void {
            $table->after('id', static function () use ($table): void {
                $table->unsignedTinyInteger('visibility')->index();
            });
        });

        $this->normalizeData();
    }

    protected function normalizeData(): void
    {
        Note::query()->update(['visibility' => Visibility::Public]);
    }
};
