<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\Actions;

use App\Filament\Resources\Articles\Pages\CreateWord;
use App\Filament\Resources\Articles\Pages\EditWord;
use App\Services\ModerationService;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

final class LanguageAdviceAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'language-advice';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Suggestief taaladvies');
        $this->icon(Heroicon::OutlinedLightBulb);
        $this->color('warning');

        // Modal configuration
        $this->modalHeading('Suggestief taaladvies');
        $this->modalDescription('Omdat we de beschrijvingen van artikelen zo neutraal mogelijk willen houden. Kunnen we inhoud van het tekstveld doorzoeken op bepaalde woorden. En als er match is voor een woord zal er hieronder een advies komen om het aan te passen.');
        $this->modalIcon(Heroicon::OutlinedLightBulb);
        $this->modalIconColor('warning');
        $this->modalWidth(Width::ThreeExtraLarge);
        $this->slideOver();
        $this->infolist(fn (EditWord|CreateWord $livewire): array => $this->getInfolist($livewire));

        $this->modalSubmitAction(false);
        $this->modalCancelActionLabel('Sluiten');
    }

    private function getInfolist(EditWord|CreateWord $livewire): array
    {
        $text = $livewire->form->getRawState()['description'] ?? '';
        $languageSuggestions = app(ModerationService::class)->analyze($text);

        if (empty($languageSuggestions)) {
            // Return a simple view for no advice, as standard components expect an array
            return [View::make('filament.actions.components.language-advice-empty')];
        }

        $components = [];

        foreach ($languageSuggestions as $s) {
            $alternativesHtml = '';
            if (!empty($s['alternatives'])) {
                $alternativesText = collect($s['alternatives'])
                    ->map(fn ($alt) => e($alt)) // Escape each alternative
                    ->implode(', ');
            }

            // Create a Section component for each suggestion, replacing the custom <div>
            $components[] = Section::make(e($s['term']))
                ->collapsible()
                ->compact()
                ->icon(Heroicon::OutlinedShieldExclamation)
                ->iconColor('warning')
                ->iconSize(IconSize::Medium)
                ->schema([
                    // Message/Description
                    TextEntry::make('message')
                        ->label('Beschrijving')
                        ->default(e($s['message']))
                        ->columnSpanFull()
                        ->prose(false) // Use prose: false to prevent text style overrides
                        ->formatStateUsing(fn ($state) => $state), // Output the escaped message

                    // Alternatives (displayed only if they exist)
                    ...($alternativesText ? [
                        TextEntry::make('alternatives')
                            // 💡 CHANGE: Use 'Overweeg:' as the label
                            ->label('Overweeg:')
                            ->columnSpanFull()
                            // Use the joined string as the default value
                            ->default($alternativesText)
                    ] : []),
                ])
                ->compact() // Make the section padding smaller
                // Use a custom CSS class to achieve the desired subtle background/border
                ->extraAttributes(['class' => 'language-advice-card'])
                ->heading(e($s['term'])); // Use the term as the section heading
        }

        return $components;
    }
}
