<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Actions;

use App\Enums\Articles\EtymologyStatus;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

/**
 * @property \App\Models\Etymology $record
 */
final class PublishEtymology extends Action
{
	use CanCustomizeProcess;
	
	public static function getDefaultName(): ?string
	{
		return EtymologyStatus::Published->getLabel();
	}
	
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->authorize('publish', $this->record);
		
		$this->icon('heroicon-o-globe-europe-africa');
		$this->color('success');
		
		$this->requiresConfirmation();
		
		$this->modalIcon('heroicon-o-globe-europe-africa');
		$this->modalCloseButton(false);
		$this->modalHeading('Etymologie publiceren');
		$this->modalDescription('U staat op het punt om meen etymologie beschikbaar te stellen voor het brede publiek.Weet u zeker dat u dit wilt doen?');
		$this->modalSubmitActionLabel('Ja, ik weet dit zeker');
		
		$this->successNotificationTitle('De etymologische gegevens zijn gepubliceerd.');
		$this->failureNotificationTitle('Helaas pindakaas! Er is iets misgelopen.');
		
		$this->action(function (): void {
			if ($this->process(fn (): bool => $this->record->state()->transitionToPublished())) {
				$this->success();
				return;
			}
			
			$this->failure();
		});
	}
}