<?php

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages;

use App\Filament\Clusters\Articles\Resources\EtymologyResource;
use App\Filament\Resources\ArticleResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\MaxWidth;

final class ViewEtymology extends ViewRecord
{
    protected static string $resource = EtymologyResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
			ActionGroup::make([
				EtymologyResource\Actions\ArchiveEtymology::make(),
				EtymologyResource\Actions\RejectEtymology::make(),
				EtymologyResource\Actions\PublishEtymology::make(),
				EtymologyResource\Actions\DraftEtymology::make(),
				EtymologyResource\Actions\UnderReviewEtymology::make(),
			])
				->button()
				->label('Markeren als')
				->color('gray')
				->icon('heroicon-o-tag'),
			
			ActionGroup::make([
				Action::make('view-article')
					->color('gray')
					->label('Bekijk gekoppeld artikel')
					->icon('heroicon-o-eye')
					->url(ArticleResource::getUrl('view', ['record' => $this->record->article])),
				
				EditAction::make()->icon('heroicon-o-pencil-square')
					->modalWidth(MaxWidth::SevenExtraLarge),
				
				ActionGroup::make([
					DeleteAction::make()->icon('heroicon-s-trash'),
				])
					->authorize('delete', $this->record)
					->dropdown(false),
			])
				->button()
				->label('Acties')
				->color('gray')
				->icon('heroicon-o-cog'),
        ];
    }
}
