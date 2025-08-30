<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources;

use App\Filament\Clusters\Articles;
use App\Filament\Clusters\Articles\Resources\DisclaimerResource\Pages;
use App\Models\Disclaimer;
use App\Policies\DisclaimerPolicy;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use App\Filament\Clusters\Articles\Resources\DisclaimerResource\Schema;

/**
 * @todo Document Disclaimer resource
 */
final class DisclaimerResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Disclaimer::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $cluster = Articles::class;

    public static function getPermissionPrefixes(): array
    {
        return DisclaimerPolicy::$permissionPrefixes;
    }

    public static function form(Form $form): Form
    {
        return Schema\FormSchema::configure($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return Schema\InfolistSchema::configure($infolist);
    }

    public static function table(Table $table): Table
    {
        return Schema\TableSchema::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDisclaimers::route('/'),
            'create' => Pages\CreateDisclaimer::route('/create'),
            'view' => Pages\ViewDisclaimer::route('/{record}'),
            'edit' => Pages\EditDisclaimer::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Cache::flexible('disclaimer_count', [10, 60], fn(): string => (string) self::$model::count());
    }
}
