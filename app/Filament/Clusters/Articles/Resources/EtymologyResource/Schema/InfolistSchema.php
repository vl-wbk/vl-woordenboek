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
            Section::make('Gegevens van de auteur en registratie')
                ->visible(fn (Etymology $etymology): bool => $etymology->author()->exists() && auth()->user()->can('viewAny', $etymology->author))
                ->icon('heroicon-s-user-circle')
                ->headerActions([
                    Action::make("view-user")
                        ->color('gray')
                        ->icon('heroicon-s-eye')
                        ->label('Bekijk gebruiker')
                        ->authorize(static function (Etymology $etymology): bool {
                            return auth()->user()->can('viewAny', $etymology->author);
                        })
                        ->url(fn (Etymology $etymology): string => UserResource::getUrl('view', ['record' => $etymology->author])),
                ])
                ->iconColor('primary')
                ->iconSize(IconSize::Medium)
                ->description('Alle gegevens omtrent de persoon die de etymologie heeft aangemaakt in het Vlaams woordenboek')
                ->compact()
                ->collapsed()
                ->columns(12)
                ->schema(self::authorInformationTab()),

            Tabs::make('Etymology-tabs')
                ->columnSpanFull()
                ->tabs([
                    self::generalInformationTab(),
                    self::sourceInformationTab(),
                    self::internalNoteTab(),
                    self::archiveInformationTab(),
                    self::rejectionInformationTab(),
                ])
        ]);
    }

    /**
     * Defines the 'Algemene informatie' tab for the Etymology Infolist.
     * This tab displays core etymology details such as status, type, origin language, origin form, period start/end, and the etymology description itself.
     *
     * @return Tab The configured 'Algemene informatie' tab.
     */
    private static function generalInformationTab(): Tab
    {
        return Tab::make('Algemene informatie')
            ->icon('heroicon-o-language')
            ->columns(12)
            ->schema([
                TextEntry::make('status')
                    ->label('Status')
                    ->columnSpan(3)
                    ->badge()
                    ->tooltip(fn (Etymology $etymology): ?string => self::getStatusTooltip($etymology)),
                TextEntry::make('type')
                    ->label('Etymologisch type')
                    ->columnSpan(3)
                    ->badge(),
                TextEntry::make('origin_language')
                    ->label('Taal van oorsprong')
                    ->columnSpan(3),
                TextEntry::make('origin_form')
                    ->label('Vorm in de brontaal')
                    ->columnSpan(3),
                TextEntry::make('period_start')
                    ->label('Periode (start)')
                    ->columnSpan(6)
                    ->date(),
                TextEntry::make('period_end')
                    ->label('Periode (eind)')
                    ->columnSpan(6)
                    ->date(),
                TextEntry::make('etymology')
                    ->label('Etymologie (beschrijving)')
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
        return Tab::make('Archiverings informatie')
            ->icon('heroicon-o-archive-box')
            ->visible(fn(Etymology $etymology): bool => $etymology->status->isArchived())
            ->columns(12)
            ->schema([
                TextEntry::make('archiver.name')
                    ->label('Gearchiveerd door')
                    ->icon('heroicon-o-user-circle')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->color('primary')
                    ->weight(FontWeight::SemiBold)
                    ->columnSpan(4),
                TextEntry::make('archived_at')
                    ->label('Tijdstip van archivering')
                    ->date()
                    ->columnSpan(4)
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary'),
                TextEntry::make('archiving_reason')
                    ->label('Beweegredenen tot de archivering')
                    ->placeholder('- Geen redenen opgegeven van de archievering van de record')
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Defines the 'Weigerings informatie' tab for the Etymology Infolist.
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
                    ->label('Afgewezen door')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->color('primary')
                    ->weight(FontWeight::SemiBold)
                    ->columnSpan(4),
                TextEntry::make('rejected_at')
                    ->label('Tijdstip van afwijzing')
                    ->date()
                    ->columnSpan(4)
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary'),
                TextEntry::make('rejection_reason')
                    ->label('Beweegredenen tot de afwijzing')
                    ->placeholder('- Geen redenen opgegeven van de afwijzing voor de record')
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Generates a tooltip string based on the etymology's status.
     * This helper method provides contextual information for the status badge, indicating who performed the action (published, rejected, archived) and when.
     *
     * @param  Etymology $etymology  The Etymology model instance.
     * @return string|null           The tooltip string, or null if no specific tooltip is defined for the status.
     */
    private static function getStatusTooltip(Etymology $etymology): ?string
    {
        return match($etymology->status) {
            EtymologyStatus::Published => trans('Gepubliceerd door :user op :time', ['user' => $etymology->author->name, 'time' => $etymology->created_at->format('d-m-Y H:i')]),
            EtymologyStatus::Rejected => trans('Afgewezen door :user op :time', ['user' => $etymology->author->name, 'time' => $etymology->created_at->format('d-m-Y H:i')]),
            EtymologyStatus::Archived => trans('Gearchiveerd door :user op :time', ['user' => $etymology->author->name, 'time' => $etymology->created_at->format('d-m-Y H:i')]),
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
                ->label('Naam')
                ->columnSpan(3)
                ->weight(FontWeight::SemiBold)
                ->icon('heroicon-o-user')
                ->iconColor('primary')
                ->color('primary')
                ->state(fn (Etymology $etymology) => $etymology->author->name),
            TextEntry::make('author.email')
                ->label('Email adres')
                ->icon('heroicon-o-envelope')
                ->iconColor('primary')
                ->columnSpan(3),
            TextEntry::make('created_at')
                ->label('Etymologie ingediend op')
                ->icon('heroicon-o-clock')
                ->iconColor('primary')
                ->columnSpan(3)
                ->date(),
            TextEntry::make('updated_at')
                ->label('Laatste wijziging (etymologie)')
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
            ->label('Bron gegevens')
            ->icon('heroicon-o-queue-list')
            ->columns(12)
            ->schema([
                TextEntry::make('source')
                    ->label('Hyperlink (tekst)')
                    ->columnSpan(4),
                TextEntry::make('source_url')
                    ->label('Hyperlink')
                    ->columnSpan(8)
                    ->placeholder('- Geen hyperlink opgegeven')
                    ->url(fn (Etymology $etymology): ?string => $etymology->source_url)
                    ->openUrlInNewTab()
            ]);
    }

    /**
     * Defines the 'Interne notitie' tab for the Etymology Infolist.
     * This tab is visible only if an internal note exists for the etymology and displays the content of that note.
     *
     * @return Tab The configured 'Interne notitie' tab.
     */
    private static function internalNoteTab()
    {
        return Tab::make('internal-note-tab')
            ->label('Interne notitie')
            ->icon('heroicon-o-chat-bubble-bottom-center-text')
            ->columns(12)
            ->visible(fn (Etymology $etymology): bool => ! is_null($etymology->note))
            ->schema([
                TextEntry::make('note')
                    ->hiddenLabel()
                    ->columnSpanFull()
            ]);
    }
}
