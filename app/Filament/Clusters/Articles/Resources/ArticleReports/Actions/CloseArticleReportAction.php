<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReports\Actions;

use App\Filament\Clusters\Articles\Resources\ArticleReports\Pages\EditArticleReport;
use App\Models\ArticleReport;
use App\States\Reporting\Status;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Schemas\Components\Callout;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;

/**
 * Represents the action for closing an article report.
 *
 * The `CloseArticleReportAction` class defines the logic and configuration for handling the closure of an article report.
 * This action transitions the report's state to "Closed" and ensures that only authorized users can perform the closure.
 *
 * This action is integrated into the Filament admin panel and provides a user-friendly interface for managing article reports.
 * It includes visual indicators, such as an icon and label, and displays success or failure notifications based on the outcome of the closure process.
 *
 * @property ArticleReport $record The database entity from the article report.
 */
final class CloseArticleReportAction extends Action
{
    use CanCustomizeProcess;

    /**
     * Configures the action's behavior and appearance.
     *
     * This method sets up the action's icon, color, label, authorization, and notifications.
     * It also defines the logic for transitioning the report's state to "Closed."
     *
     * - The icon is dynamically retrieved from the "Closed" state.
     * - The authorization ensures that only users with the "markAsClosed" permission can perform the action.
     * - Success and failure notifications are displayed based on the outcome of the action.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->icon(Status::Closed->getIcon());
        $this->color('gray');
        $this->label('melding afsluiten');
        $this->authorize('markAsClosed', $this->record);

        $this->requiresConfirmation();
        $this->modalIconColor(Status::Closed->getColor());
        $this->modalIcon(Status::Closed->getIcon());
        $this->modalWidth(Width::TwoExtraLarge);
        $this->modalDescription('U staat op het punt om een melding te sluiten. Geef hier nog even op waarom u de melding afsluit: is die afgehandeld, niet relevant, of is er iets anders aan de hand?');

        $this->schema(schema: $this->getActionFormSchema());

        $this->successNotificationTitle('Het ticket is succesvol afgesloten');
        $this->failureNotificationTitle('Helaas konden we het ticket niet afsluiten wegens een technische fout');

        $this->action(function (array $data): void {
            /** @var ArticleReport $record */
            $record = $this->getRecord();

            $livewire = $this->getLivewire();

            if ($livewire instanceof EditArticleReport) {
                $livewire->saveQuietly();
            }

            if ($this->process(fn (): bool => $record->status()->transitionToClosed($data['result']))) {
                $this->success();

                return;
            }

            $this->failure();
        });
    }

    /**
     * @return array<int, Callout|Textarea>
     */
    protected function getActionFormSchema(): array
    {
        return [
            Callout::make('Kan de melding niet afsluiten')
                ->description('De ingevulde velden in het formulier zijn niet conform met de validatieregels, sluit deze pop-up en verhelp de problemen.')
                ->iconSize(IconSize::Medium)
                ->icon(Heroicon::OutlinedExclamationTriangle)
                ->hidden(fn (): bool => $this->getLivewire()->getErrorBag()->isEmpty())
                ->danger(),

            Textarea::make('result')
                ->label('Eindbesluit')
                ->required()
                ->placeholder('Beschrijf kort wat je hebt gedaan om de melding te verhelpen.')
                ->rows(4),
        ];
    }

    /**
     * Returns the default name for the action.
     *
     * The default name is used to identify the action within the Filament admin panel.
     * In this case, the name is set to "close-report."
     *
     * @return string The default name of the action.
     */
    public static function getDefaultName(): string
    {
        return 'close-report';
    }
}
