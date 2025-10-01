<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Blogs\Pages;

use App\Filament\Clusters\Blog\Resources\Blogs\BlogResource;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Resources\Pages\ListRecords;

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

    /**
     * Defines the header actions available on the ListBlogs page.
     *
     * This method configures actions that appear at the top of the list records page.
     * Currently, it includes a `FactoryAction` which is set to a 'danger' color.
     * This action likely provides functionality to generate new blog records, possibly for testing or seeding purposes.
     *
     * @return array<int, FactoryAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            FactoryAction::make()
                ->color('danger'),
        ];
    }
}
