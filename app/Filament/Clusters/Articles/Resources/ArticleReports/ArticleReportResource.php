<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReports;

use App\Attributes\Todo;
use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Pages\EditArticleReport;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Pages\ListArticleReports;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Schema\ReportForm;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Schema\ReportInfolist;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Schema\TableSchema;
use App\Filament\Clusters\Articles\Resources\ArticleReports\Widgets\ArticleReportingChartWidget;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\ArticleReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use Override;
use UnitEnum;

/**
 * Represents the resource for managing article reports in the admin panel.
 *
 * The `ArticleReportResource` class defines the configuration for displaying and managing article reports within the Filament admin panel.
 * It integrates with the `ArticleReport` model and provides tools for administrators and moderators to view, manage, and act on reports submitted by users.
 *
 * This resource includes configurations for:
 * - The `infolist`, which displays detailed information about a report.
 * - The `table`, which provides an overview of all reports with actions for managing them.
 * - Navigation badges and routes for accessing the resource's pages.
 *
 * The resource is part of the `Articles` cluster and centralizes the logic for managing article reports, ensuring consistency and maintainability.
 */
final class ArticleReportResource extends Resource
{
    use HasActiveIcon;

    /**
     * Specifies the model associated with this resource.
     * This property links the `ArticleReportResource` to the `ArticleReport` model, ensuring that the resource operates on the correct data.
     */
    protected static ?string $model = ArticleReport::class;

    /**
     * Specifies the icon used for the resource in the navigation menu.
     * The icon visually represents the resource in the admin panel's navigation.
     */
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-flag';

    protected static string|UnitEnum|null $navigationGroup = 'Gegevens';

    /**
     * Specifies the singular label for the resource.
     * This label is used in the admin panel to refer to a single instance of the resource.
     */
    protected static ?string $modelLabel = 'melding';

    /**
     * Specifies the plural label for the resource.
     * This label is used in the admin panel to refer to multiple instances of the resource.
     */
    protected static ?string $pluralModelLabel = 'Meldingen';

    /**
     * Specifies the cluster to which this resource belongs.
     * The cluster groups related resources together for better organization in the admin panel.
     *
     * {@inheritDoc}
     */
    protected static ?string $cluster = ArticlesCluster::class;

    /**
     * Configures the infolist for displaying detailed information about a report.
     *
     * The infolist includes sections and fieldsets that display general information about the report, follow-up details, and user feedback.
     * It also provides header actions for viewing related user and article information.
     */
    #[Todo(message: 'refactor this into an infolist schema')]
    public static function infolist(Schema $schema): Schema
    {
        return ReportInfolist::configure($schema);
    }

    /**
     * Configures the table with columns, filters, and actions for managing article reports.
     * Includes empty state messaging and a description for context.
     */
    public static function table(Table $table): Table
    {
        return TableSchema::configure($table);
    }

    public static function form(Schema $schema): Schema
    {
        return ReportForm::configure($schema);
    }

    public static function getNavigationBadge(): ?string
    {
        return Cache::flexible('report_count', [10, 60], function (): string {
            return (string) self::$model::whereNull('closed_at')->count();
        });
    }

    /**
     * @return array<string, mixed> The array of page routes.
     */
    #[Override]
    public static function getPages(): array
    {
        return array_merge(parent::getPages(), [
            'index' => ListArticleReports::route('/'),
            'edit' => EditArticleReport::route('/{record}'),
        ]);
    }

    /**
     * @return array<class-string> An array of widget class names that will be rendered on the dashboard
     */
    public static function getWidgets(): array
    {
        return array_merge(parent::getWidgets(), [
            ArticleReportingChartWidget::class,
        ]);
    }
}
