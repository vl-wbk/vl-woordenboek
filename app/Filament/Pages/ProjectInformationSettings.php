<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\ProjectInformationSettings as SettingsProjectInformationSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Support\Enums\IconSize;

/**
 * Class ProjectInformationSettings
 *
 * This Filament Settings Page provides a user interface for managing the project information page in the front-end of the application.
 * It allows administrators to configure the content, title, and visibility of the page.
 *
 * @package App\Filament\Pages
 */
final class ProjectInformationSettings extends SettingsPage
{
    use HasPageShield;

    /**
     * Defines the icon used to represent this settings page in the Filament admin panel navigation menu. Uses a Tabler icon.
     */
    protected static string | \BackedEnum | null $navigationIcon = 'tabler-file-info';

    /**
     * Specifies the Filament cluster that this settings page belongs to.
     *
     * {@inheritDoc}
     */
    protected static ?string $cluster = SettingsCluster::class;

    /**
     * Defines the settings class associated with this page.
     */
    protected static string $settings = SettingsProjectInformationSettings::class;

    /**
     * Defines the navigation group that this settings page belongs to in the Filament admin panel.
     */
    protected static string | \UnitEnum | null $navigationGroup = "Pagina's";

    /**
     * Defines the title displayed at the top of this settings page in the Filament admin panel.
     */
    protected static ?string $title = 'Project informatie';

    /**
     * Configures the form used to display and edit the project information settings.
     *
     * This method defines the form schema, which includes a section for page configuration.
     * It uses Filament form components to create a user-friendly interface for managing these settings.
     *
     * @param \Filament\Schemas\Schema $schema The Filament form builder instance.
     * @return \Filament\Schemas\Schema The configured Filament form instance.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project informatie pagina')
                    ->description('Configureer hier de inhoud van de project informatie pagina in de front-end van de applicatie')
                    ->icon(self::$navigationIcon)
                    ->iconColor('primary')
                    ->iconSize(IconSize::Medium)
                    ->compact()
                    ->columns(12)
                    ->schema($this->pageSettingsFormDefinition()),
            ]);
    }

    /**
     * Defines the form schema for the page settings section.
     * This method creates an array of Filament form components used to configure the title, content, and visibility of the project information page.
     *
     * @return array<int, TextInput|MarkdownEditor|Toggle> An array of Filament form components.
     */
    private function pageSettingsFormDefinition(): array
    {
        return [
            TextInput::make('pageTitle')
                ->label('Pagina titel')
                ->columnSpan(9),
            MarkdownEditor::make('pageContent')
                ->label('Pagina inhoud')
                ->columnSpanFull()
                ->disableToolbarButtons(['attachFiles', 'codeBlock', 'table']),
            Toggle::make('pageActive')
                ->label('Deze pagina is publiek toegankelijk')
                ->columnSpanFull()
                ->live(),
        ];
    }
}
