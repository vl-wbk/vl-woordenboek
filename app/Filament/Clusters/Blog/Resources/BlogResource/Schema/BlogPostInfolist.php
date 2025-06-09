<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Schema;

use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use App\Models\Blog;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\TextColumn;

final readonly class BlogPostInfolist
{
    public static function getComponent(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema(components: [
                Tabs::make('article-information-tabs')
                    ->columnSpan(12)
                    ->tabs(tabs: [
                        self::getArticleContentTab(),
                        self::getPublicationInformationTab(),
                    ])
            ]);
    }

    private static function getArticleContentTab(): Tab
    {
        return Tab::make('Artikel informatie')
            ->icon('heroicon-s-document-text')
            ->columns(12)
            ->schema(components: [
                TextEntry::make('author.name')
                    ->columnSpan(3)
                    ->weight(FontWeight::SemiBold)
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary'),
                TextEntry::make('title')
                    ->label('Titel')
                    ->columnSpan(7)
                    ->translateLabel(),
                TextEntry::make('category.name')
                    ->label('Categorieen')
                    ->translateLabel()
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-tag')
                    ->placeholder('- Geen categorieen gekoppeld aan het nieuwsartikel')
                    ->columnSpanFull(),
                TextEntry::make('content')
                    ->label('Artikel inhoud')
                    ->translateLabel()
                    ->columnSpanFull()
                    ->markdown()
                    ->placeholder('- Geen inhoud voor dit artikel gevonden'),
            ]);
    }

    private static function getPublicationInformationTab(): Tab
    {
        return Tab::make('Publicatie informatie')
            ->icon('heroicon-s-globe-europe-africa')
            ->visible(fn (Blog $blog): bool => $blog->status->isPublished())
            ->columns(12)
            ->schema(components: [
                TextEntry::make('publisher.name')
                    ->label('Gepubliceerd door')
                    ->translateLabel()
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->columnSpan(3)
                    ->weight(FontWeight::SemiBold),

                IconEntry::make('comments_enabled')
                    ->label('Reacties toegestaan')
                    ->boolean()
                    ->columnSpan(3),

                TextEntry::make('views')
                    ->label('Weergaves')
                    ->translateLabel()
                    ->formatStateUsing(fn ($state): string => $state . ' weergave(s)')
                    ->badge()
                    ->columnSpan(3)
                    ->icon('heroicon-o-eye'),

                TextEntry::make('published_at')
                    ->label('Publicatie datum')
                    ->translateLabel()
                    ->date()
                    ->columnSpan(3)
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary')
                    ->placeholder('- Geen publicatie datum bekend')
            ]);
    }
}
