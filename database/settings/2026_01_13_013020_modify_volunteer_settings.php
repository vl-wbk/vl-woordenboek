<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->deleteIfExists('volunteers.positions');
        $this->migrator->deleteIfExists('volunteers.pageContent');

        $this->migrator->add('volunteers.pageTagLine', 'Goestingske om bij te dragen aan het Vlaams Woordenboek?');
        $this->migrator->add('volunteers.questionsTitle', 'Nog vragen?');
        $this->migrator->add('volunteers.questionsContent', 'Twijfel je welke rol bij je past? We helpen je graag verder.');
        $this->migrator->add('volunteers.whyHelpTitle', 'Waarom helpen?');
        $this->migrator->add('volunteers.whyHelpContent', 'Het Vlaams Woordenboek is een non-profit project. Jouw bijdrage zorgt ervoor dat ons taal niet verloren gaat voor de volgende generaties.');
    }
};
