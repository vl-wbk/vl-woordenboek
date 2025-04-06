<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReportResource\Actions;

use App\Models\ArticleReport;
use App\States\Reporting\Status;
use Filament\Actions\DeleteAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;

final class ViewAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return trans('bekijken');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel();
        $this->icon('heroicon-o-eye');
        $this->modalIcon('heroicon-o-eye');
        $this->modalIconColor('highlight');
        $this->modalWidth(MaxWidth::SixExtraLarge);
        $this->modalHeading('Melding informatie');
        $this->infolist([TextEntry::make('description')->hiddenlabel()->columnSpanFull()]);

        $this->modalDescription(function (ArticleReport $articleReport): string {
            return trans(':user heeft op :date de volgende melding ingestuurd voor het woord :article.', [
                'user' => $this->record->author->name, 'date' => $articleReport->created_at->format('d/m/Y'), 'article' => $articleReport->article->word
            ]);
        });

        $this->modalFooterActions([
                Action::make('Behandelen')
                    ->icon(Status::InProgress->getIcon())
                    ->color('gray'),

                Action::make('Behandeld')
                    ->color('gray')
                    ->icon(Status::Closed->getIcon()),

                DeleteAction::make()
            ]);
    }
}
