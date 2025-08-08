<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Filament\Resources\FeedbackResource\Schema;
use App\Models\Feedback;
use App\Policies\FeedbackPolicy;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

/**
 * @todo Document this class
 * @todo Provide additional end user documentation
 */
final class FeedbackResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function getPermissionPrefixes(): array
    {
        return FeedbackPolicy::$permissionPrefixes;
    }

    public static function table(Table $table): Table
    {
        return Schema\TableSchema::configure($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return Schema\InfolistSchema::configure($infolist);
    }

    public static function getWidgets(): array
    {
        /** @phpstan-ignore-next-line */
        return [
            \App\Filament\Resources\FeedbackResource\Widgets\FeedbackStatisticsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedback::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $feedbackCount = Cache::flexible('feedback_count', [10, 60], fn(): string => (string) self::$model::count());

        // Return the count if it's greater than 0, otherwise return null
        return $feedbackCount > 0 ? $feedbackCount : null;
    }
}
