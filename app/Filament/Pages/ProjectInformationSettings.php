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

final class ProjectInformationSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'tabler-file-info';

    protected static ?string $cluster = Settings::class;

    protected static string $settings = SettingsProjectInformationSettings::class;

    protected static ?string $navigationGroup = "Pagina's";

    protected static ?string $title = 'Project informatie';

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

    public static function canAccess(): bool
    {
        return auth()->user()->user_type->in([UserTypes::Administrators, UserTypes::Developer]);
    }
}
