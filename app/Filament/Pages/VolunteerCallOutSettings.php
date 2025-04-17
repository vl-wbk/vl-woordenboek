<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\VolunteerSettings;
use App\Enums\VolunteerPositions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\SettingsPage;
use Filament\Support\Enums\IconSize;

final class VolunteerCallOutSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $cluster = Settings::class;

    protected static string $settings = VolunteerSettings::class;

    protected static ?string $navigationLabel = 'Oproep voor vrijwilligers';

    protected static ?string $title = 'Oproep voor vrijwilligers';

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

    private function pageSettingsFormDefinition(): array
    {
        return [
            TextInput::make('pageTitle')
                ->columnSpan(9),
            MarkdownEditor::make('pageContent')
                ->columnSpanFull()
                ->disableToolbarButtons(['attachFiles', 'codeBlock', 'table']),
            Toggle::make('pageActive')
                ->label('Deze pagina is publiek toegankelijk')
                ->columnSpanFull()
                ->live(),
        ];
    }

    private function volunteerOpeningsForm(): array
    {
        return [
            CheckboxList::make('positions')
                ->hiddenLabel()
                ->options(VolunteerPositions::class)
                ->columns(3)
        ];
    }
}
