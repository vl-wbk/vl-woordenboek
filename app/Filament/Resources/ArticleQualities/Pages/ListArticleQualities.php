<?php

namespace App\Filament\Resources\ArticleQualities\Pages;

use App\Filament\Resources\ArticleQualities\ArticleQualityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArticleQualities extends ListRecords
{
    protected static string $resource = ArticleQualityResource::class;
}
