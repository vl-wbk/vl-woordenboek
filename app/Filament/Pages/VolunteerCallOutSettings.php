<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\VolunteerSettings;
use App\Enums\VolunteerPositions;
use App\UserTypes;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Support\Enums\IconSize;

/**
 * Class VolunteerCallOutSettings
 *
 * This Filament Settings Page provides a user interface for managing the volunteer call-out section of the application.
 * It allows administrators to configure the content, visibility, and available positions for volunteers.
 *
 * It leverages the `spatie/laravel-settings` package to store and retrieve the configuration values.
 *
 * @package App\Filament\Pages
 */
final class VolunteerCallOutSettings extends SettingsPage
{
    /**
     * Defines the icon used to represent this settings page in the Filament admin panel navigation menu.
     * This helps users visually identify the page within the admin interface. Uses a Heroicon name.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    /**
     * Specifies the Filament cluster that this settings page belongs to. Clusters are used to
     * group related settings pages together, providing a more organized admin interface.
     *
     * @var string|null
     */
    protected static ?string $cluster = Settings::class;

    /**
     * Defines the settings class associated with this page.
     * This class determines which settings can be configured and managed through this Filament page.
     *
     * @var string
     */
    protected static string $settings = VolunteerSettings::class;

    /**
     * Sets the label used for this settings page in the Filament admin panel navigation menu.
     * This is the human-readable name that users will see in the menu.
     *
     * @var string|null
     */
    protected static ?string $navigationLabel = 'Oproep voor vrijwilligers';

    /**
     * Defines the title displayed at the top of this settings page in the Filament admin panel.
     * This provides context and helps users understand the purpose of the page.
     *
     * @var string|null
     */
    protected static ?string $title = 'Oproep voor vrijwilligers';

    protected static ?string $navigationGroup = "Pagina's";

    /**
     * Configures the form used to display and edit the volunteer call-out settings.
     *
     * This method defines the form schema, which includes sections for page configuration and volunteer positions.
     * It uses Filament form components to create a user-friendly interface for managing these settings.
     *
     * @param  Form $form  The Filament form builder instance.
     * @return Form        The configured Filament form instance.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Pagina configuratie')
                    ->description('Hier kun je alle benodigde informatie configureren die te zal zijn op de informatieve pagina die gaat over de vrijwilligerswerving')
                    ->icon('heroicon-o-document-text')
                    ->iconSize(IconSize::Medium)
                    ->iconColor('primary')
                    ->columns(12)
                    ->compact()
                    ->headerActions([
                        Action::make('view-page')
                            ->label('bekijk pagina')
                            ->icon('heroicon-o-globe-europe-africa')
                            ->url(route('support.volunteers'))
                            ->openUrlInNewTab()
                            ->color('gray')
                            ->visible(fn (VolunteerSettings $volunteerSettings) => $volunteerSettings->pageActive)

                    ])
                    ->schema($this->pageSettingsFormDefinition()),

                Section::make('Vrijwilligers posities')
                    ->description('Hier configureer je de openstaande posities voor de vrijwilligers. De gebruiker kan deze aanvinken naar voorkeur bij de aanmelding')
                    ->icon('heroicon-o-users')
                    ->iconSize(IconSize::Medium)
                    ->iconColor('primary')
                    ->compact()
                    ->schema($this->volunteerOpeningsForm()),
            ]);
    }

    /**
     * Defines the form schema for the page settings section.
     * This method creates an array of Filament form components used to configure the title, content, and visibility of the volunteer call-out page.
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
     * Defines the form schema for the volunteer openings section.
     *
     * This method creates an array of Filament form components used to manage the available volunteer positions.
     * It uses a CheckboxList to allow administrators to select multiple positions.
     *
     * @return array<int, CheckboxList>  An array containing a single CheckboxList component.
     */
    private function volunteerOpeningsForm(): array
    {
        return [
            CheckboxList::make('positions')
                ->hiddenLabel()
                ->options(VolunteerPositions::class)
                ->columns(3)
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->user_type->in([UserTypes::Administrators, UserTypes::Developer]);
    }
}
