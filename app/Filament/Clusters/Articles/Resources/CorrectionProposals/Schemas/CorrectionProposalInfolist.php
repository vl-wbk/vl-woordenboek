<?php 

declare(strict_types=1); 

namespace App\Filament\Clusters\Articles\Resources\CorrectionProposals\Schemas;

use App\Models\CorrectionProposal;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;

final readonly class CorrectionProposalInfolist
{
    public static function configure(Schema $infolist): Schema
    {
        return $infolist->schema(components: [
            CorrectionProposalForm::correctionInformationTabs(),
            self::moderationInformationSection(), 

            TextEntry::make('description')
                ->label('Voorgestelde correctie (artikel beschrijving)')
                ->markdown()
                ->color('gray')
                ->columnSpanFull(),
        ]);
    }

    private static function moderationInformationSection(): Section
    {
        return Section::make('Redactie gegevens')
            ->description('Gegevens omtrent de conclusie van de correctie en wie deze behandeld heeft?')
            ->icon(Heroicon::OutlinedCheckBadge)
            ->iconColor(fn (CorrectionProposal $correctionProposal): string => $correctionProposal->state->getColor())
            ->compact()
            ->columns(12)
            ->columnSpanFull()
            ->collapsible()
            ->collapsed()
            ->schema(components: [
                TextEntry::make('moderator.name')
                    ->label('Behandeld door')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->iconColor('primary')
                    ->weight(FontWeight::ExtraBold)
                    ->color('primary')
                    ->columnSpan(4), 

                TextEntry::make('moderated_at')
                    ->label('Behandeld op')
                    ->date()
                    ->columnSpan(4),
                
                TextEntry::make('conclusion')
                    ->label('Conclusie')
                    ->columnSpanFull()
                    ->placeholder('n.v.t. of niet opgegeven'),
            ]);
    }
}