<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\Actions;

use App\Enums\Articles\ArchiveReason;
use App\Models\Article;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\LazyCollection;

/**
 * @todo Write docblocks for this action class.
 */
final class BulkArchiveAction extends BulkAction
{
    public static function getDefaultName(): string
    {
        return 'bulk-archive-articles';
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Action button configuration
        $this->label('Artikelen archiveren');
        $this->icon(Heroicon::OutlinedArchiveBox);

        // Modal setup
        $this->modalHeading('Meerdere artikelen archiveren');
        $this->modalIcon(Heroicon::OutlinedArchiveBox);
        $this->modalCloseButton(false);
        $this->modalDescription('U staat op het punt om meerdere artikelen te archiveren. Via het onderstaande formulier kunt u een gemeenschappelijke reden opgeven.');
        $this->schema(schema: $this->configureSchemaComponents());

        // Success notification Setup
        $this->successNotificationTitle('Artikelen succesvol gearchiveerd');

        // Failure Notification setup
        $this->failureNotificationTitle(fn (int $successCount, int $totalCount): string => $this->getFailureNotificationHeading($successCount, $totalCount));
        $this->failureNotificationBody(fn (int $successCount, int $totalCount): string => $this->getFailureNotificationBodyContent($successCount, $totalCount));

        // Misc. setup
        $this->chunkSelectedRecords(20);
        $this->authorizeIndividualRecords('archive-article');
        $this->requiresConfirmation();
        $this->deselectRecordsAfterCompletion();

        // Handling setup
        $this->action(action: function (Collection|LazyCollection $records, array $data) {
            // @phpstan-ignore-next-line
            $records->each(fn (Article $article): bool => $article->articleStatus()->transitionToArchived($data['archiving_reason']));
        });

    }

    /**
     * @return array<int, Select|Textarea>
     */
    private function configureSchemaComponents(): array
    {
        return [
            Select::make('reason')
                ->label('Reden tot archivering')
                ->options(ArchiveReason::class)
                ->native(false)
                ->afterStateUpdated(fn (Set $set, ?ArchiveReason $state) => $set('archiving_reason', $state->getDescription()))
                ->live(),

            Textarea::make('archiving_reason')
                ->rows(4)
                ->label(label: __('filament/actions/archiveArticle.form.archiving-reason.label'))
                ->placeholder(placeholder: __('filament/actions/archiveArticle.form.archiving-reason.placeholder'))
                ->maxLength(350)
                ->helperText(new HtmlString('Deze tekst zal <strong>zichtbaar</strong> zijn voor de eindgebruiker.'))
                ->visible(fn (Get $get) => $get('archiving_reason') !== null || $get('reason') === ArchiveReason::Other)
                ->default(null),
        ];
    }

    private function getFailureNotificationBodyContent(int $successCount, int $totalCount): string
    {
        if ($successCount) {
            return __('We hebben :successCount van de :totalCount geselecteerde artikelen gearchiveerd', [
                'successCount' => $successCount,
                'totalCount' => $totalCount
            ]);
        }

        return __('We konden geen enkel artikel archiveren. Mogelijks voldoen ze niet aan de benodigde criteria');
    }

    private function getFailureNotificationHeading(int $successCount, int $totalCount): string
    {
        if ($successCount) {
            return __(':successCount van de :totalCount artikelen gearchiveerd', [
                'successCount' => $successCount,
                'totalCount' => $totalCount
            ]);
        }

        return __('Geen artikelen gearchiveerd');
    }
}
