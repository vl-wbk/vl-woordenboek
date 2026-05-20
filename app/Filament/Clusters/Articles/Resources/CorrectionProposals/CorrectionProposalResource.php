<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\CorrectionProposals;

use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\CorrectionProposals\Pages\ListCorrectionProposals;
use App\Filament\Clusters\Articles\Resources\CorrectionProposals\Pages\EditCorrectionProposal;
use App\Filament\Clusters\Articles\Resources\CorrectionProposals\Schemas\CorrectionProposalForm;
use App\Filament\Clusters\Articles\Resources\CorrectionProposals\Schemas\CorrectionProposalInfolist;
use App\Filament\Clusters\Articles\Resources\CorrectionProposals\Tables\CorrectionProposalsTable;
use App\Filament\Support\Concerns\HasActiveIcon;
use BackedEnum;
use App\Models\CorrectionProposal;
use App\States\Articles\Corrections\PendingState;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use Override;

final class CorrectionProposalResource extends Resource
{
    use HasActiveIcon; 

    protected static ?string $model = CorrectionProposal::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationLabel = 'Correctie voorstellen';

    protected static ?string $pluralModelLabel = 'Correctie voorstellen';

    protected static ?string $modelLabel = 'Correctioneel voorstel';

    protected static ?string $cluster = ArticlesCluster::class;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return CorrectionProposalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CorrectionProposalsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCorrectionProposals::route('/'),
            'edit' => EditCorrectionProposal::route('/{record}'),
        ];
    }

    #[Override]
    public static function getNavigationBadge(): ?string
    {
        return Cache::flexible('correct:count:badge', [10, 120], function (): string {
            return (string) CorrectionProposal::whereState('state', PendingState::class)->count('id');
        });
    }
}
