<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class () extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('volunteers.pageActive', false);
        $this->migrator->add('volunteers.pageTitle');
        $this->migrator->add('volunteers.pageContent');
        $this->migrator->add('volunteers.positions', []);
    }
};
