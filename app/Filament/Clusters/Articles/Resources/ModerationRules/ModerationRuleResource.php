<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ModerationRules;

use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\ModerationRules\Pages\CreateModerationRule;
use App\Filament\Clusters\Articles\Resources\ModerationRules\Pages\EditModerationRule;
use App\Filament\Clusters\Articles\Resources\ModerationRules\Pages\ListModerationRules;
use App\Filament\Clusters\Articles\Resources\ModerationRules\Schemas\ModerationRuleForm;
use App\Filament\Clusters\Articles\Resources\ModerationRules\Tables\ModerationRulesTable;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\ModerationRule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

/**
 * @todo Write docblocks for this class
 */
final class ModerationRuleResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = ModerationRule::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $cluster = ArticlesCluster::class;

    protected static ?string $modelLabel = 'Taaladvies';

    protected static ?string $pluralModelLabel = 'Taaladviezen';

    protected static ?string $recordTitleAttribute = 'pattern';

    /**
     * The label used for grouping the resource in the navigation sidebar.
     *
     * @var string|UnitEnum|null
     */
    protected static string|UnitEnum|null $navigationGroup = 'Ondersteuning';

    public static function form(Schema $schema): Schema
    {
        return ModerationRuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ModerationRulesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListModerationRules::route('/'),
            'create' => CreateModerationRule::route('/create'),
            'edit' => EditModerationRule::route('/{record}/edit'),
        ];
    }
}
