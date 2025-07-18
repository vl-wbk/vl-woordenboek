<?php

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages;

use App\Filament\Clusters\Articles\Resources\EtymologyResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

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
				EditAction::make()->icon('heroicon-o-pencil-square'),
				
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
