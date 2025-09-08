<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema;

use App\Enums\Articles\EtymologyStatus;
use App\Filament\Resources\UserResource;
use App\Models\Etymology;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Auth;

/**
 * Defines the Infolist schema for displaying Etymology resource details in Filament.
 *
 * This class provides a static method to configure an Infolist, organizing etymology data into sections and tabs for a comprehensive view.
 * It includes information about the author, general etymology details, source information, internal notes, and specific details for archived or rejected etymologies.
 *
 * @package App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema
 */
final readonly class InfolistSchema
{
    /**
     * Configures the Infolist for the Etymology resource.
     * This method defines the layout and components of the Infolist, including a section for author and registration data, and a set of tabs for various etymology-related information.
     *
     * @param  Infolist $infolist   The Infolist instance to configure.
     * @return Infolist             The configured Infolist instance.
     */
    public static function configure(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make(heading: __('etymology-resource.infolist.heading'))
                ->visible(static fn(Etymology $etymology): bool => $etymology->author()->exists() && Auth::user()->can('viewAny', $etymology->author))
                ->icon('heroicon-s-user-circle')
                ->headerActions([
                    Action::make("view-user")
                        ->color('gray')
                        ->icon('heroicon-s-eye')
                        ->label(label: __('etymology-resource.header-actions.view-user.label'))
                        ->authorize(static fn(Etymology $etymology): bool => Auth::user()->can('viewAny', $etymology->author))
                        ->url(fn(Etymology $etymology): string => UserResource::getUrl('view', ['record' => $etymology->author])),
                ])
                ->iconColor('primary')
                ->iconSize(IconSize::Medium)
                ->description(description: __('etymology-resource.infolist.description'))
                ->compact()
                ->collapsed()
                ->columns(12)
                ->schema(self::authorInformationTab()),

            Tabs::make('Etymology-tabs')
                ->columnSpanFull()
                ->tabs([
                    self::generalInformationTab(),
                    self::sourceInformationTab(),
                    self::archiveInformationTab(),
                    self::rejectionInformationTab(),
                ]),
        ]);
    }

    private static function generalInformationTab(): Tab
    {
        return Tab::make(label: __('etymology-resource.infolist.general-information-tab.label'))
            ->icon('heroicon-o-language')
            ->columns(12)
            ->schema([
                TextEntry::make('status')
                    ->label(label: __('etymology-resource.infolist.general-information-tab.entries.status'))
                    ->columnSpan(3)
                    ->badge()
                    ->tooltip(fn(Etymology $etymology): string|array|null => self::getStatusTooltip($etymology)),

                TextEntry::make('origin')
                    ->label(label: __('etymology-resource.infolist.general-information-tab.entries.origin'))
                    ->columnSpan(6)
                    ->color('gray'),

                TextEntry::make('origin_period')
                    ->label(label: __('etymology-resource.infolist.general-information-tab.entries.origin-period'))
                    ->columnSpan(3)
                    ->color('gray'),

                TextEntry::make('further_development')
                    ->label(label: __('etymology-resource.infolist.general-information-tab.entries.further-development'))
                    ->color('gray')
                    ->columnSpan(9),

                TextEntry::make('further_development_period')
                    ->label(label: __('etymology-resource.infolist.general-information-tab.entries.further-development-period'))
                    ->color('gray')
                    ->columnSpan(3),

                TextEntry::make('additional_info')
                    ->label(label: __('etymology-resource.infolist.general-information-tab.entries.additional-info'))
                    ->color('gray')
                    ->placeholder('-')
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Defines the 'Archiverings informatie' tab for the Etymology Infolist.
     * This tab is visible only if the etymology status is 'Archived' and displays details about who archived the record, when it was archived, and the reason.
     *
     * @return Tab The configured 'Archiverings informatie' tab.
     */
    private static function archiveInformationTab(): Tab
    {
        return Tab::make(label: __('etymology-resource.infolist.archive-information-tab.label'))
            ->icon('heroicon-o-archive-box')
            ->visible(fn(Etymology $etymology): bool => $etymology->status->isArchived())
            ->columns(12)
            ->schema([
                TextEntry::make('archiver.name')
                    ->label(label: __('etymology-resource.infolist.archive-information-tab.entries.archiver'))
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->color('primary')
                    ->weight(FontWeight::SemiBold)
                    ->columnSpan(4),

                TextEntry::make('archived_at')
                    ->label(label: __('etymology-resource.infolist.archive-information-tab.entries.timestamp'))
                    ->date()
                    ->columnSpan(4)
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary'),

                TextEntry::make('archiving_reason')
                    ->label(label: __('etymology-resource.infolist.archive-information-tab.entries.reason.label'))
                    ->placeholder(placeholder: __('etymology-resource.infolist.archive-information-tab.entries.reason.placeholder'))
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Defines the 'Weigering informatie' tab for the Etymology Infolist.
     * This tab is visible only if the etymology status is 'Rejected' and displays details about who rejected the record, when it was rejected, and the reason.
     *
     * @return Tab The configured 'Weigerings informatie' tab.
     */
    private static function rejectionInformationTab(): Tab
    {
        return Tab::make('Weigerings informatie')
            ->visible(fn(Etymology $etymology): bool => $etymology->status->isRejected())
            ->icon('heroicon-o-x-circle')
            ->columns(12)
            ->schema([
                TextEntry::make('rejecter.name')
                    ->label(label: __('etymology-resource.infolist.rejection-information-tab.entries.rejecter-name'))
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->color('primary')
                    ->weight(FontWeight::SemiBold)
                    ->columnSpan(4),

                TextEntry::make('rejected_at')
                    ->label(label: __('etymology-resource.infolist.rejection-information-tab.entries.rejection-timestamp'))
                    ->date()
                    ->columnSpan(4)
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary'),

                TextEntry::make('rejection_reason')
                    ->label(label: __('etymology-resource.infolist.rejection-information-tab.entries.reason.label'))
                    ->placeholder(placeholder: __('etymology-resource.infolist.rejection-information-tab.entries.reason.placeholder'))
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Generates a tooltip string based on the etymology's status.
     * This helper method provides contextual information for the status badge, indicating who performed the action (published, rejected, archived) and when.
     *
     * @param  Etymology $etymology  The Etymology model instance.
     * @return array<int>|string|null     The tooltip string, or null if no specific tooltip is defined for the status.
     */
    private static function getStatusTooltip(Etymology $etymology): array|string|null
    {
        return match ($etymology->status) {
            EtymologyStatus::Published => trans('etymology-resource.infolist.general-information-tab.tooltip.published', [
                'user' => $etymology->author->name,
                'time' => $etymology->created_at->format('d-m-Y H:i'),
            ]),
            EtymologyStatus::Rejected => trans('etymology-resource.infolist.general-information-tab.tooltip.rejected', [
                'user' => $etymology->author->name,
                'time' => $etymology->created_at->format('d-m-Y H:i'),
            ]),
            EtymologyStatus::Archived => trans('etymology-resource.infolist.general-information-tab.tooltip.archived', [
                'user' => $etymology->author->name,
                'time' => $etymology->created_at->format('d-m-Y H:i'),
            ]),

            default => null,
        };
    }

    /**
     * Defines the schema for the author information section.
     * This method returns an array of TextEntry components displaying details about the etymology's author, including their name, email, and creation/update timestamps.
     *
     * @return array<TextEntry> An array of TextEntry components for author information.
     */
    private static function authorInformationTab(): array
    {
        return [
            TextEntry::make('author.name')
                ->label(label: __('etymology-resource.infolist.author-information-tab.entries.name'))
                ->columnSpan(3)
                ->weight(FontWeight::SemiBold)
                ->icon('heroicon-o-user')
                ->iconColor('primary')
                ->color('primary')
                ->state(fn(Etymology $etymology) => $etymology->author->name),

            TextEntry::make('author.email')
                ->label(label: __('etymology-resource.infolist.author-information-tab.entries.name'))
                ->icon('heroicon-o-envelope')
                ->iconColor('primary')
                ->columnSpan(3),

            TextEntry::make('created_at')
                ->label(label: __('etymology-resource.infolist.author-information-tab.entries.created-at'))
                ->icon('heroicon-o-clock')
                ->iconColor('primary')
                ->columnSpan(3)
                ->date(),

            TextEntry::make('updated_at')
                ->label(label: __('etymology-resource.infolist.author-information-tab.entries.edited-at'))
                ->icon('heroicon-o-clock')
                ->iconColor('primary')
                ->columnSpan(3)
                ->date(),
        ];
    }

    /**
     * Defines the 'Bron gegevens' tab for the Etymology Infolist.
     * This tab displays information about the source of the etymology, including a text label for the hyperlink and the actual URL.
     *
     * @return Tab The configured 'Bron gegevens' tab.
     */
    private static function sourceInformationTab(): Tab
    {
        return Tab::make('source-information-tab')
            ->label(label: __('etymology-resource.infolist.source-information-tab.label'))
            ->icon('heroicon-o-queue-list')
            ->columns(12)
            ->schema([
                TextEntry::make('oldest_find_spot')
                    ->columnSpan(3)
                    ->label(label: __('etymology-resource.infolist.source-information-tab.entries.oldest-find-spot'))
                    ->color('gray'),

                TextEntry::make('oldest_find_period')
                    ->columnSpan(3)
                    ->label(label: __('etymology-resource.infolist.source-information-tab.entries.oldest-find-period'))
                    ->color('gray'),

                TextEntry::make('source_name')
                    ->label(label: __('etymology-resource.infolist.source-information-tab.entries.source-name'))
                    ->color('gray')
                    ->columnSpan(3),

                TextEntry::make('source_hyperlink')
                    ->label(label: __('etymology-resource.infolist.source-information-tab.entries.source-hyperlink.label'))
                    ->columnSpan(3)
                    ->color('gray')
                    ->placeholder(placeholder: __('etymology-resource.infolist.source-information-tab.entries.source-hyperlink.placeholder'))
                    ->url(fn(Etymology $etymology): string => $etymology->source_url ?? __('etymology-resource.infolist.source-information-tab.entries.source-hyperlink.url'))
                    ->openUrlInNewTab(),
            ]);
    }
}
