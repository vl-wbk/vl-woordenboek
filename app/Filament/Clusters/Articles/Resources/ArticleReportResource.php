<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;
use Filament\Support\Enums\Width;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Pages\ListArticleReports;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Pages\ViewArticleReport;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Widgets\ArticleReportingChartWidget;
use Filament\Schemas\Components\Fieldset;
use App\Filament\Clusters\Articles;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Actions\TableActionsConfiguration;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Pages;
use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Schema\TableColumnSchema as SchemaTableColumnSchema;
use App\Filament\Resources\ArticleResource\Pages\ViewWord;
use App\Filament\Resources\UserResource;
use App\Models\ArticleReport;
use App\Models\User;
use App\States\Reporting\Status;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

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
 *
 * @package App\Filament\Clusters\Articles\Resources
 */
final class ArticleReportResource extends Resource implements HasShieldPermissions
{
    /**
     * Specifies the model associated with this resource.
     * This property links the `ArticleReportResource` to the `ArticleReport` model, ensuring that the resource operates on the correct data.
     */
    protected static ?string $model = ArticleReport::class;

    /**
     * Specifies the icon used for the resource in the navigation menu.
     * The icon visually represents the resource in the admin panel's navigation.
     */
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-flag';

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
    protected static ?string $cluster = Articles::class;

    /**
     * @return list<string>
     */
    public static function getPermissionPrefixes(): array
    {
        return ['view_any', 'view', 'mark_in_progress', 'mark_as_closed', 'delete', 'delete_any'];
    }

    /**
     * Configures the infolist for displaying detailed information about a report.
     *
     * The infolist includes sections and fieldsets that display general information about the report, follow-up details, and user feedback.
     * It also provides header actions for viewing related user and article information.
     *
     * @param \Filament\Schemas\Schema $schema The infolist instance to configure.
     * @return \Filament\Schemas\Schema The configured infolist instance.
     */
    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                Section::make()
                    ->heading('Algemene informatie van de melding')
                    ->headerActions([
                        Action::make('reporter-information')
                            ->authorize('viewAny', User::class)
                            ->label('bekijk melder')
                            ->icon('tabler-user-search')
                            ->color('gray')
                            ->url(fn(ArticleReport $articleReport): string => UserResource::getUrl('view', ['record' => $articleReport->author])),
                        Action::make('article-information')
                            ->label('bekijk artikel')
                            ->icon('tabler-eye-search')
                            ->color('gray')
                            ->url(fn(ArticleReport $articleReport): string => ViewWord::getUrl(['record' => $articleReport->article])),
                    ])
                    ->description(fn(ArticleReport $articleReport): string => trans(':user heeft op :date de volgende melding ingestuurd.', [
                        'user' => $articleReport->author->name, 'date' => $articleReport->created_at->format('d/m/Y'),
                    ]))
                    ->icon('tabler-message-user')
                    ->iconSize(IconSize::Medium)
                    ->iconColor('highlight')
                    ->compact()
                    ->columns(12)
                    ->schema(components: [self::followUpFieldset(), self::feedbackFieldset()]),
            ]);
    }

    /**
     * Configures the table for displaying an overview of all reports.
     *
     * The table includes columns, actions, and bulk actions for managing reports.
     * It also provides an empty state message when no reports are available.
     *
     * @param  Table $table  The table instance to configure.
     * @return Table         The configured table instance.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->heading(self::$pluralModelLabel)
            ->description(self::tableDescription())
            ->headerActions(TableActionsConfiguration::headerActions())
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen meldingen gevonden')
            ->emptyStateDescription('Het lijk erop dat er momenteel geen openstaande meldingen zijn die gerelateerd zijn aan de atikelen van het Vlaams Woordenboek.')
            ->columns(SchemaTableColumnSchema::make())
            ->recordActions(TableActionsConfiguration::rowActions())
            ->toolbarActions(TableActionsConfiguration::bulkActions())
            ->filtersFormWidth(Width::Medium)
            ->filters(self::getTableFilters());
    }

    /**
     * Provides a description for the table.
     * This description explains the purpose of the table and its role in displaying user-submitted reports.
     *
     * @return string The table description.
     */
    private static function tableDescription(): string
    {
        return trans('Soms kan het zijn dat er een foutje sluipt in een woordenboek artikel en gebruikers deze melden. Deze table is een overzicht van alle meldingen die zijn uitgevoerd door een gebruiker.');
    }

    /**
     * @todo provide docblock
     * @return array<int, SelectFilter|Filter>
     */
    public static function getTableFilters(): array
    {
        return [
            SelectFilter::make('state')
                ->options(Status::class)
                ->label('Status')
                ->multiple()
                ->default([Status::Open->value, Status::InProgress->value]),
            Filter::make('assigned')
                ->label('Toegewezen aan mij')
                ->query(fn(Builder $query): Builder => $query->where('assignee_id', auth()->id())),
        ];
    }

    /**
     * Retrieves the navigation badge for the resource.
     *
     * The badge displays the total count of reports in the navigation menu.
     * The count is cached for performance optimization.
     *
     * @return string|null The navigation badge value.
     */
    public static function getNavigationBadge(): ?string
    {
        return Cache::flexible('report_count', [10, 60], fn(): string => (string) self::$model::count());
    }

    /**
     * Defines the pages associated with this resource.
     * The pages include a list view and a detailed view for managing reports.
     *
     * @return array<string, mixed> The array of page routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => ListArticleReports::route('/'),
            'view' => ViewArticleReport::route('/{record}'),
        ];
    }

    /**
     * Defines the widgets that appear on the ArticleReportResource dashboard.
     *
     * This method configures which widgets are available when viewing article reports.
     * Currently, it includes the ArticleReportingChartWidget, which provides visual analytics and reporting trends through charts and graphs.
     * This widget helps administrators track and analyze report patterns over time.
     *
     * Future developers can add additional widgets to this array to extend the dashboard's functionality.
     * Each widget should be related to article reporting and provide valuable insights or tools for report management.
     *
     * @return array<class-string> An array of widget class names that will be rendered on the dashboard
     */
    public static function getWidgets(): array
    {
        return [
            ArticleReportingChartWidget::class,
        ];
    }

    /**
     * Configures the fieldset for follow-up details.
     * This fieldset displays information about the report's status, assignee, and timestamps for assignment and closure.
     *
     * @return \Filament\Schemas\Components\Fieldset The configured fieldset instance.
     */
    private static function followUpFieldset(): Fieldset
    {
        return Fieldset::make('Gegevens omtrent de opvolging')
            ->columns(12)
            ->schema(components: [
                TextEntry::make('state')
                    ->label('Status')
                    ->badge()
                    ->columnSpan(3),
                TextEntry::make('assignee.name')
                    ->label('Opgevolgd door')
                    ->color('highlight')
                    ->iconColor('highlight')
                    ->weight(FontWeight::SemiBold)
                    ->icon('heroicon-o-user-circle')
                    ->columnSpan(3)
                    ->placeholder('geen opvolger geregistreerd'),
                TextEntry::make('assigned_at')
                    ->label('Toegewezen op')
                    ->icon('heroicon-o-clock')
                    ->iconColor('highlight')
                    ->columnSpan(3)
                    ->date(),
                TextEntry::make('closed_at')
                    ->label('Afgesloten op')
                    ->icon('heroicon-o-clock')
                    ->iconColor('highlight')
                    ->columnSpan(3)
                    ->placeholder('-')
                    ->date(),
            ]);
    }

    /**
     * Configures the fieldset for follow-up details.
     * This fieldset displays information about the report's status, assignee, and timestamps for assignment and closure.
     *
     * @return \Filament\Schemas\Components\Fieldset The configured fieldset instance.
     */
    private static function feedbackFieldset(): Fieldset
    {
        return Fieldset::make('Door de gebruiker gegeven feedback')
            ->schema(components: [
                TextEntry::make('description')
                    ->columnSpan(12)
                    ->hiddenLabel(),
            ]);
    }
}
