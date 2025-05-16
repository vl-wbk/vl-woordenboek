<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\ProjectInformationSettings as SettingsProjectInformationSettings;
use App\UserTypes;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
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
    /**
     * Defines the icon used to represent this settings page in the Filament admin panel navigation menu. Uses a Tabler icon.
     */
    protected static ?string $navigationIcon = 'tabler-file-info';

    /**
     * Specifies the Filament cluster that this settings page belongs to.
     *
     * {@inheritDoc}
     */
    protected static ?string $cluster = Settings::class;

    /**
     * Defines the settings class associated with this page.
     */
    protected static string $settings = SettingsProjectInformationSettings::class;

    /**
     * Defines the navigation group that this settings page belongs to in the Filament admin panel.
     */
    protected static ?string $navigationGroup = "Pagina's";

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
     * @param  Form $form   The Filament form builder instance.
     * @return Form         The configured Filament form instance.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Project informatie pagina')
                    ->description('Configureer hier de inhoud van de project informatie pagina in de front-end van de applicatie')
                    ->icon(self::$navigationIcon)
                    ->iconColor('primary')
                    ->iconSize(IconSize::Medium)
                    ->compact()
                    ->columns(12)
                    ->schema($this->pageSettingsFormDefinition())
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

    /**
     * Determines whether the user can access this settings page.
     *
     * This method checks if the currently authenticated user has the `Administrators` or `Developer` user type.
     * Only users with these user types are allowed to access this settings page.
     *
     * @return bool True if the user can access the page, false otherwise.
     */
    public static function canAccess(): bool
    {
        return auth()->user()->user_type->in([UserTypes::Administrators, UserTypes::Developer]);
    }
}
