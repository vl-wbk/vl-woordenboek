<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Blogs\Pages;

use App\Features\DocumentationButtons;
use App\Filament\Clusters\Blog\Resources\Blogs\BlogResource;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Laravel\Pennant\Feature;

/**
 * ListBlogs
 *
 * This class extends Filament's ListRecords page, providing a dedicated interface for listing and managing blog records within the Filament admin panel.
 * It configures the associated resource and defines header actions available on this page.
 *
 * @package App\Filament\Clusters\Blog\Resources\BlogResource\Pages
 */
final class ListBlogs extends ListRecords
{
    /**
     * The resource associated with this list page.
     * This property links the ListBlogs page to the BlogResource, enabling Filament to correctly display and manage blog entries.
     */
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('help')
                ->visible(Feature::active(DocumentationButtons::class))
                ->icon('heroicon-o-lifebuoy'),

            ActionGroup::make([
                CreateAction::make('artikel aanmaken')
                    ->color('gray')
                    ->icon('heroicon-o-document-plus'),
                FactoryAction::make()
                    ->modalHeading('Genereer test nieuweberichten')
                    ->modalDescription('Genereer test nieuweberichten voor de blogsectie, deze kunnen worden gebruikt om te testen of de applicatie werkt zoals verwacht.'),
            ])->buttonGroup(),
        ];
    }
}
