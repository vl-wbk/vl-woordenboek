<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Schema\TableSchema;
use App\Filament\Resources\FeedbackResource\Schema\InfolistSchema;
use App\Filament\Resources\FeedbackResource\Widgets\FeedbackStatisticsWidget;
use App\Filament\Resources\FeedbackResource\Pages\ListFeedback;
use App\Enums\FeedbackStatus;
use App\Filament\Resources\FeedbackResource\Pages;
use App\Filament\Resources\FeedbackResource\Schema;
use App\Models\Feedback;
use App\Policies\FeedbackPolicy;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    /**
     * @return list<string>
     */
    public static function getPermissionPrefixes(): array
    {
        return FeedbackPolicy::$permissionPrefixes;
    }

    public static function table(Table $table): Table
    {
        return TableSchema::configure($table);
    }

    public static function infolist(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return InfolistSchema::configure($schema);
    }

    public static function getWidgets(): array
    {
        /** @phpstan-ignore-next-line */
        return [
            FeedbackStatisticsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeedback::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $feedbackCount = Cache::flexible(
            key: 'feedback_count',
            ttl: [10, 60],
            callback: fn(): string => (string) self::$model::where('status', FeedbackStatus::Unprocessed)->count(),
        );

        // Return the count if it's greater than 0, otherwise return null
        return $feedbackCount > 0 ? $feedbackCount : null;
    }
}
