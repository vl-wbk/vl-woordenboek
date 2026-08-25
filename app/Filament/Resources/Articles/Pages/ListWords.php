<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Pages;

use App\Features\DocumentationButtons;
use App\Filament\Clusters\Articles\Resources\ArticleResource\Widgets\ArticleRegistrationChart;
use App\Filament\Exports\ArticleExporter;
use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Etymology;
use App\Models\Label;
use App\Models\Note;
use App\Models\Reaction;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Models\Export;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Laravel\Pennant\Feature;

/**
 * Dictionary Article Management Interface
 *
 * This Filament page serves as the central hub for managing dictionary entries through their various lifecycle states.
 * The interface presents articles in an organized tabbed layout, where each tab corresponds to a distinct article state such as draft, published, or under review.
 *
 * The page maintains state persistence between sessions by remembering the active tab selection. Additionally,
 * it employs a caching mechanism to efficiently display the number of articles in each state through color-coded badges.
 *
 * Through the header interface, editors can initiate the creation of new dictionary entries directly from any view.
 * This streamlined workflow enables efficient content management while maintaining clear visibility of the editorial process.
 *
 * @property string $activeTab The currently selected article state tab
 *
 * @package App\Filament\Resources\ArticleResource\Pages
 */
final class ListWords extends ListRecords
{
    /**
     * Filament Resource Configuration
     *
     * Establishes the core resource class that powers this listing interface.
     * The ArticleResource contains all foundational settings for managing dictionary entries, including field definitions, validation rules, and relationship configurations.
     * This static property binds the listing page to its corresponding resource implementation, enabling Filament to properly render the interface and handle data operations.
     *
     * The ArticleResource drives the behavior of this tabbed interface, determining how dictionary entries are displayed, filtered, and interacted with throughout the editorial workflow.
     */
    protected static string $resource = ArticleResource::class;

    /**
     * Retrieves the header widgets for the page.
     *
     * This method returns an array of Filament widgets that should be displayed in the header of the page.
     * In this case, it returns the `ArticleRegistrationChart` widget, which displays a chart of article registrations.
     *
     * @return array<mixed>
     */
    protected function getHeaderWidgets(): array
    {
        return [ArticleRegistrationChart::class];
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('docs')
                    ->label('Help')
                    ->visible(Feature::active(DocumentationButtons::class))
                    ->icon('heroicon-o-lifebuoy')
                    ->url('https://vl-wbk.github.io/documentatie-portaal/artikelen/')
                    ->openUrlInNewTab(),
            ])->buttonGroup(),

            ActionGroup::make([
                CreateAction::make()
                    ->color('gray')
                    ->icon('heroicon-o-document-plus'),

                ExportAction::make()
                    ->visible(auth()->user()->can('create', Export::class))
                    ->exporter(ArticleExporter::class)
                    ->modalWidth(Width::Large)
                    ->modalDescription(description: __('Gegevens nodig in een ander programma? Geen probleem! Selecteer de kolommen die je nodig hebt en je kunt vervolgens de gegevens downloaden in een .xlsx of .csv bestanden downloaden'))
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->chunkSize(250)
                    ->slideOver(),

                FactoryAction::make()
                    ->color('gray')
                    ->hiddenLabel()
                    ->modalHeading('Genereer test artikelen')
                    ->modalIcon(Heroicon::OutlinedCog8Tooth)
                    ->modalDescription('Genereer test artikelen voor het woordenboek, deze kunnen worden gebruikt om te testen of de applicatie werkt zoals verwacht.')
                    ->icon('heroicon-s-cog-8-tooth')
                    ->hasMany([Note::class, Etymology::class, Reaction::class])
                    ->belongsToMany([Label::class]),
            ])->buttonGroup(),
        ];
    }
}
