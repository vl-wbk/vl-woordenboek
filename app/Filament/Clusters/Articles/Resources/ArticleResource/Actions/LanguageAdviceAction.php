<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\Actions;

use App\Filament\Resources\Articles\Pages\CreateWord;
use App\Filament\Resources\Articles\Pages\EditWord;
use App\Services\ModerationService;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

final class LanguageAdviceAction extends Action
{
    public static function getDefaultName(): string
    {
        return 'language-advice';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Gevoelig taaladvies');
        $this->icon(Heroicon::OutlinedLightBulb);
        $this->color('warning');

        $this->modalHeading('Gevoelig taaladvies');
        $this->modalDescription('Omdat we de beschrijvingen van artikelen zo neutraal mogelijk willen houden...');
        $this->modalWidth(Width::ThreeExtraLarge);
        $this->slideOver();

        // Ensure the return type here matches the method signature
        $this->schema(fn (EditWord|CreateWord $livewire): array => $this->getInfolist($livewire));

        $this->modalSubmitAction(false);
        $this->modalCancelActionLabel('Sluiten');
    }

    /**
     * @return array<int, Section|ViewEntry>
     */
    private function getInfolist(EditWord|CreateWord $livewire): array
    {
        /** @var array<string, mixed> $state */
        $state = $livewire->form->getRawState();
        $text = is_string($state['description'] ?? null) ? $state['description'] : '';

        $languageSuggestions = app(ModerationService::class)->analyze($text);

        if (empty($languageSuggestions)) {
            return [
                ViewEntry::make('empty_advice')
                    ->view('filament.actions.components.language-advice-empty')
            ];
        }

        $components = [];

        foreach ($languageSuggestions as $s) {
            // REMOVED is_array($s) check as it's already narrowed.

            $alternativesText = '';
            $alternatives = $s['alternatives'] ?? [];

            if (is_iterable($alternatives) && !empty($alternatives)) {
                $alternativesText = collect($alternatives)
                    // Use transform and filter to ensure we only have strings
                    ->map(fn ($alt) => is_string($alt) ? e($alt) : '')
                    ->filter()
                    ->implode(', ');
            }

            // FIX: Use helper to safely get string values from the mixed array
            $term = $this->ensureString($s['term'] ?? 'Onbekend term');
            $category = $this->ensureString($s['category'] ?? 'Algemeen');
            $message = $this->ensureString($s['message'] ?? '');

            $components[] = Section::make($term)
                ->description('Categorie: ' . $category)
                ->collapsible()
                ->compact()
                ->icon(Heroicon::OutlinedShieldExclamation)
                ->iconColor('warning')
                ->schema([
                    TextEntry::make('message')
                        ->label('Beschrijving')
                        ->default($message)
                        ->columnSpanFull()
                        ->prose(false),

                    ...($alternativesText !== '' ? [
                        TextEntry::make('alternatives')
                            ->label('Overweeg:')
                            ->columnSpanFull()
                            ->default($alternativesText)
                    ] : []),
                ])
                ->extraAttributes(['class' => 'language-advice-card']);
        }

        return $components;
    }

    private function ensureString(mixed $value): string
    {
        return is_string($value) ? $value : '';
    }
}
