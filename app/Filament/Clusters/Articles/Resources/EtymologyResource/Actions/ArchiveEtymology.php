<?php

declare(strict_types=1);
	
namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Actions;

use App\Enums\Articles\EtymologyStatus;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Textarea;

/**
 * @property \App\Models\Etymology $record
 */
final class ArchiveEtymology extends Action
{
	use CanCustomizeProcess;
	
	public static function getDefaultName(): ?string
	{
		return EtymologyStatus::Archived->getLabel();
	}
	
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->authorize('archive', $this->record);
		
		$this->icon('heroicon-o-archive-box');
		$this->color('warning');
		
		$this->requiresConfirmation();
		
		$this->modalIcon('heroicon-o-archive-box');
		$this->modalCloseButton(false);
		$this->modalHeading('Etymologie archiveren');
		$this->modalDescription('U staat op het punt om etymologische gegevens te archiveren. Bent u zeker dat u deze handeling wilt uitvoeren?');
		$this->modalSubmitActionLabel('Ja, ik ben zeker');
		
		$this->successNotificationTitle('De gegevens zijn gearchiveerd');
		$this->failureNotificationTitle('Helaas pindakaas! Er is iets migelopen.');
		
		$this->form([
			Textarea::make('reason')
				->label('Reden van de archivering')
				->placeholder('Beschrijf kort waarom je de gegevens wilt archiveren.')
				->rows(5)
				->required()
		]);
		
		$this->action(function (): void {
			if ($this->process(fn (array $data): bool => $this->record->state()->transitionToArchived($data['reason']))) {
				$this->success();
				return;
			}
			
			$this->failure();
		});
	}
}