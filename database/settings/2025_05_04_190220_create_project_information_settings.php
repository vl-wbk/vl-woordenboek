<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('projectInformation.pageActive', false);
        $this->migrator->add('projectInformation.pageTitle', 'Project Informatie');
        $this->migrator->add('projectInformation.pageContent', null);
    }
};
