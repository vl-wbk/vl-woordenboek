<?php

namespace App\Filament\Pages;

use App\Enums\ArticleStates;
use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Resources\Articles\Widgets\RedactionQueueKpis;
use App\Filament\Resources\Articles\Widgets\RedactionQueueTable;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\Article;
use App\UserTypes;
use BackedEnum;
use Filament\Pages\Dashboard;

class RedactionDashboard extends Dashboard
{
    use HasActiveIcon;

    protected static string $routePath = 'redaction-dashboard';

    protected static ?string $cluster = ArticlesCluster::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $title = 'Eindredactie wachtrij';

    public function getWidgets(): array
    {
        return [
            RedactionQueueKpis::class,
            RedactionQueueTable::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Article::where('state', ArticleStates::Approval)->count();
    }

    public static function canAccess(): bool
    {
        return auth()->user()->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer, UserTypes::EditorInChief])
            && auth()->user()->getPreference('uitgeschakelde grafieken');
    }
}
