<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Pages;

use App\Filament\Clusters\Blog\Resources\BlogResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Page class responsible for handling the creation of new blog posts in the admin panel.
 *
 * This class extends Filament's CreateRecord functionality, providing a user-friendly interface for administrators or editors to add new blog entries.
 * Beyond simply saving the form data, it includes custom logic to automatically associate the newly created blog post with the currently authenticated user, ensuring that authorship is always tracked and attributed correctly.
 *
 * The association is handled in the afterCreate() hook, which is triggered immediately after the blog post is saved to the database. This means that every new blog post will have its 'author' relationship set to the user who created it, without requiring any manual intervention or additional form fields.
 * This approach helps maintain data integrity and makes it easy to display or filter blog posts by author throughout the application.
 *
 * @property \App\Models\Blog $record The database entity from the current authenticat user.
 *
 * @package App\Filament\lusters\BlogResources\BlogResource\Pages
 */
final class CreateBlog extends CreateRecord
{
    /**
     * Specifies the resource this page is linked to.
     * This ensures the correct model, form, and table configuration are used.
     *
     * @var class-string<BlogResource>
     */
    protected static string $resource = BlogResource::class;

    /**
     * Hook that runs after a new blog post is created.
     *
     * Automatically associates the current authenticated user as the author of the blog post.
     * This guarantees that every blog post has a valid author and simplifies future queries or displays that rely on author information.
     */
    protected function afterCreate(): void
    {
        $this->record->author()->associate(auth()->user())->save();
    }
}
