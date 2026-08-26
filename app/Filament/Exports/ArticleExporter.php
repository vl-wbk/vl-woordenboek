<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Article;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

/**
 * Exporter class for the Article model.
 *
 * This class is responsible for defining the columns, formats, and other settings for exporting article data from the Filament admin panel.
 * It provides a standardized way to generate downloadable reports in formats like CSV and XLSX, ensuring data consistency and readability.
 *
 * @package App\Filament\Exports
 */
final class ArticleExporter extends Exporter
{
    /**
     * The model that this exporter is for.
     */
    protected static ?string $model = Article::class;

    /**
     * Define the columns that will be included in the export.
     *
     * @return array<ExportColumn> An array of `ExportColumn` objects.
     */
    public static function getColumns(): array
    {
        return [
            self::createExportColumn(name: 'id', label: 'ID'),
            self::createExportColumn(name: 'views', label: 'Weergaves', enableByDefault: false),
            self::createExportColumn(name: 'keywords', label: 'Kernwoorden'),
            self::createExportColumn(name: 'word', label: 'Woord'),
            self::createExportColumn(name: 'characteristics', label: 'Karakteristieken'),
            self::createExportColumn(name: 'description', label: 'Beschrijving'),
            self::createExportColumn(name: 'example', label: 'Voorbeeld gebruik'),

            self::createExportColumn(name: 'origin', label: 'Herkomst van het artikel', enableByDefault: false)
                ->state(static fn(Article $article): string => optional($article->origin)->getLabel() ?? '-'),

            self::createExportColumn(name: 'state', label: 'Status in het woordenboek', enableByDefault: false)
                ->state(static fn(Article $article): string => optional($article->state)->getLabel() ?? 'onbekend'),

            self::createExportColumn(name: 'partOfSpeech.name', label: 'Woordsoort'),
            self::createExportColumn(name: 'regions.name', label: 'Regio'),
            self::createExportColumn(name: 'status', label: 'Status')
                ->state(static fn(Article $article): string => optional($article->status)->getLabel() ?? '-'),

            self::createExportColumn(name: 'author.name', label: 'Auteur', enableByDefault: false),
            self::createExportColumn(name: 'editor.name', label: 'Redacteur', enableByDefault: false),
            self::createExportColumn(name: 'publisher.name', label: 'Gepubliceerd door', enableByDefault: false),
            self::createExportColumn(name: 'archiever.name', label: 'Gearchiveerd door', enableByDefault: false),
        ];
    }

    /**
     * Generates the notification body for a completed export job.
     * This method constructs a user-friendly message that summarizes the export result, including the number of successful rows and any failed rows, to be displayed as a notification.
     *
     * @param Export $export The export model instance.
     * @return string The completed notification body.
     */
    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = trans('Uw artikel export is gereed en bevat :amount item(s)', [
            'amount' => toHumanReadableNumber((int) $export->successful_rows),
        ]);

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('rij')->plural($failedRowsCount) . ' konden niet gexporteerd worden.';
        }

        return $body;
    }

    /**
     * Get the supported export formats for this exporter.
     *
     * @return array<ExportFormat> An array of `ExportFormat` enum cases.
     */
    public function getFormats(): array
    {
        return [
            ExportFormat::Csv,
            ExportFormat::Xlsx,
        ];
    }

    /**
     * Generates a dynamic filename for the exported file.
     * The filename includes a unique key from the export job to prevent naming conflicts and ensure each download is distinct.
     *
     * @param   Export $export  The export model instance.
     * @return  string          The generated filename.
     */
    public function getFileName(Export $export): string
    {
        return "vlaams-woordenboek-artikelen-{$export->getKey()}";
    }

    /**
     * Provides a batch name for the export job.
     * This name can be used to group and monitor related export jobs in the job queue, making it easier to manage background processes.
     *
     * @return string The job batch name, or null if not needed.
     */
    public function getJobBatchName(): string
    {
        return 'dictionary-articles-export';
    }

    /**
     * A private helper method to create an `ExportColumn` instance.
     *
     * This method simplifies the creation of export columns by providing default values and a consistent interface.
     * It helps maintain a clean and readable `getColumns` method.
     *
     * @param   string  $name            The name of the column.
     * @param   string  $label           The human-readable label for the column header.
     * @param   bool   $enableByDefault  Whether the column should be enabled by default.
     * @return  ExportColumn             A new `ExportColumn` instance.
     */
    private static function createExportColumn(string $name, string $label, bool $enableByDefault = true): ExportColumn
    {
        return ExportColumn::make($name)
            ->label($label)
            ->enabledByDefault($enableByDefault);
    }
}
