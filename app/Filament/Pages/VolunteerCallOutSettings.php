<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Support\Concerns\HasActiveIcon;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;
use App\Settings\VolunteerSettings;
use App\Enums\VolunteerPositions;
use App\Filament\Clusters\Volunteers\VolunteersCluster;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use UnitEnum;

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
    use HasActiveIcon;

    /**
     * Defines the icon used to represent this settings page in the Filament admin panel navigation menu.
     * This helps users visually identify the page within the admin interface. Uses a Heroicon name.
     */
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    /**
     * Specifies the Filament cluster that this settings page belongs to. Clusters are used to
     * group related settings pages together, providing a more organized admin interface.
     *
     * {@inheritDoc}
     */
    protected static ?string $cluster = VolunteersCluster::class;

    /**
     * Defines the settings class associated with this page.
     * This class determines which settings can be configured and managed through this Filament page.
     */
    protected static string $settings = VolunteerSettings::class;

    /**
     * Sets the label used for this settings page in the Filament admin panel navigation menu.
     * This is the human-readable name that users will see in the menu.
     */
    protected static ?string $navigationLabel = 'Oproep voor vrijwilligers';

    protected static UnitEnum|string|null $navigationGroup = "Pagina's";

    /**
     * Defines the title displayed at the top of this settings page in the Filament admin panel.
     * This provides context and helps users understand the purpose of the page.
     */
    protected static ?string $title = 'Oproep voor vrijwilligers';

    /**
     * Configures the form used to display and edit the volunteer call-out settings.
     *
     * This method defines the form schema, which includes sections for page configuration and volunteer positions.
     * It uses Filament form components to create a user-friendly interface for managing these settings.
     *
     * @param  Schema $schema The Filament form builder instance.
     * @return Schema         The configured Filament form instance.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagina configuratie')
                    ->description('Hier kun je alle benodigde informatie configureren die te zal zijn op de informatieve pagina die gaat over de vrijwilligerswerving')
                    ->icon('heroicon-o-document-text')
                    ->iconSize(IconSize::Medium)
                    ->iconColor('primary')
                    ->columns(12)
                    ->columnSpanFull()
                    ->compact()
                    ->headerActions([
                        Action::make('view-page')
                            ->label('bekijk pagina')
                            ->icon('heroicon-o-globe-europe-africa')
                            ->url(route('support.volunteers'))
                            ->openUrlInNewTab()
                            ->color('gray')
                            ->visible(fn (VolunteerSettings $volunteerSettings): bool => $volunteerSettings->pageActive),

                    ])
                    ->schema($this->pageSettingsFormDefinition()),
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
            Tabs::make('Tabs')
                ->columnSpanFull()
                ->columns(12)
                ->tabs([
                    Tab::make('Algemeen')
                        ->icon(Heroicon::OutlinedInformationCircle)
                        ->schema([
                            TextInput::make('pageTitle')
                                ->label('Pagina titel')
                                ->columnSpan(9),
                            Textarea::make('pageTagLine')
                                ->label('Tag line')
                                ->characterLimit(200)
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    Tab::make('Vragen')
                        ->icon(Heroicon::OutlinedEnvelopeOpen)
                        ->schema([
                            TextInput::make('questionsTitle')
                                ->label('Titel')
                                ->columnSpan(7),
                            TextInput::make('questionsEmail')
                                ->label('Contact adres')
                                ->placeholder('name@domain.tld')
                                ->required()
                                ->columnSpan(5)
                                ->email(),
                            Textarea::make('questionsContent')
                                ->label('Inhoud')
                                ->characterLimit(200)
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),

                    self::selectionProcedureSettingsTab(),

                    Tab::make('Waarom helpen')
                        ->icon(Heroicon::OutlinedQuestionMarkCircle)
                        ->schema([
                            TextInput::make('whyHelpTitle')
                                ->label('Titel')
                                ->columnSpan(9),
                            Textarea::make('whyHelpContent')
                                ->label('Inhoud')
                                ->characterLimit(200)
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    Tab::make('Zichtbaarheid')
                        ->icon(Heroicon::OutlinedEye)
                        ->schema([
                            Toggle::make('pageActive')
                                ->onColor('success')
                                ->offColor('danger')
                                ->onIcon(Heroicon::Check)
                                ->offIcon(Heroicon::XMark)
                                ->label('De gebruiker kan de algemene informatie pagina bekijken.')
                                ->columnSpanFull()
                                ->live(),

                            Toggle::make('pageSelectionProcedureActive')
                                ->onColor('success')
                                ->offColor('danger')
                                ->onIcon(Heroicon::Check)
                                ->offIcon(Heroicon::XMark)
                                ->label('De gebruiker kan de informatie omtrent de selectie procedure bekijken')
                                ->columnSpanFull()
                                ->live(),

                            Toggle::make('pageRegistrationActive')
                                ->onColor('success')
                                ->offColor('danger')
                                ->onIcon(Heroicon::Check)
                                ->offIcon(Heroicon::XMark)
                                ->label('De gebruiker kan zich aanmelden voor vrijwilligers posities')
                                ->columnSpanFull()
                                ->live(),
                        ]),
                ])
                ->vertical(),

        ];
    }

    private static function selectionProcedureSettingsTab(): Tab
    {
        return Tab::make('Selectie procedure')
            ->icon(Heroicon::OutlinedClipboardDocumentList)
            ->schema([
                Repeater::make('procedure')
                    ->columnSpanFull()
                    ->hiddenLabel()
                    ->compact()
                    ->collapsible()
                    ->collapsed()
                    ->reorderable()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->schema([
                        TextInput::make('title')
                            ->label('title')
                            ->required()
                            ->live(),

                        TextInput::make('subtitle')
                            ->label('Sub titel'),

                        Textarea::make('description')
                            ->label('Beschrijving van de stap')
                            ->rows(4)
                            ->required()
                            ->characterLimit(400)
                    ])
            ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()->can('vrijwilligers-pagina-wijzigen');
    }
}
