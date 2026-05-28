<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReports\Pages;

use App\Filament\Clusters\Articles\Resources\ArticleReports\Actions\CloseArticleReportAction;
use App\Filament\Clusters\Articles\Resources\ArticleReports\ArticleReportResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\ArticleReport;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Represents the page for viewing a single article report in the admin panel.
 *
 * The `EditArticleReport` class extends Filament's `ViewRecord` class to provide a detailed view of an article report's data.
 * It is part of the `ArticleReportResource` and integrates various actions for managing the report's lifecycle.
 *
 * This page is designed for administrators and moderators to review the details of a report and take actions such as assigning the report to themselves, closing the report, or deleting it.
 * The available actions are displayed in the page header for quick access.
 */
final class EditArticleReport extends EditRecord
{
    protected static string $resource = ArticleReportResource::class;

    public function getBreadcrumb(): string
    {
        return 'Behandelen';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Melding behandelen';
    }

    /**
     * @return array<Action> An array of configured header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('reporter-information')
                ->hidden(fn (ArticleReport $articleReport): bool => $articleReport->author()->doesntExist())
                ->authorize('viewAny', User::class)
                ->label('bekijk melder')
                ->icon('tabler-user-search')
                ->color('gray')
                ->url(fn (ArticleReport $articleReport): string => UserResource::getUrl('view', ['record' => $articleReport->author])),

            CloseArticleReportAction::make(),
            DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->icon(Heroicon::OutlinedCheck)
                ->label('Opslaan')
                ->requiresConfirmation()
                ->modalHeading('Wijzigingen opslaan')
                ->modalDescription('Ben je zeker dat je de wijzigingen wilt opslaan?')
                ->modalSubmitActionLabel('Ja, opslaan')
                ->action(fn () => $this->save()),

            $this->getCancelFormAction()
                ->icon(icon: Heroicon::OutlinedXCircle),
        ];
    }
}
