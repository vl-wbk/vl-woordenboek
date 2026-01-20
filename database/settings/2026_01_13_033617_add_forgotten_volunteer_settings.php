<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('volunteers.procedure', []);
        $this->migrator->add('volunteers.questionsEmail');
        $this->migrator->add('volunteers.pageRegistrationActive', false);
        $this->migrator->add('volunteers.pageSelectionProcedureActive', false);
    }
};
