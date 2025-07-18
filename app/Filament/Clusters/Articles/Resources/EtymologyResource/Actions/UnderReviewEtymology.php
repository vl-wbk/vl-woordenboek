<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Actions;

use App\Enums\Articles\EtymologyStatus;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

/**
 * @property \App\Models\Etymology $record
 */
final class UnderReviewEtymology extends Action
{
	use CanCustomizeProcess;
	
	public static function getDefaultName(): ?string
	{
		return EtymologyStatus::UnderReview->getLabel();
	}
	
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->authorize('underReview', $this->record);
		
		$this->icon('heroicon-o-paper-airplane');
		$this->color('success');
		
		$this->requiresConfirmation();
		
		$this->modalIcon('heroicon-o-paper-airplane');
		$this->modalCloseButton(false);
		$this->modalHeading('Etymology in review plaatsen');
		$this->modalDescription('Bij het plaatsen van de etymologie in review. Zal deze ingezonden worden ter beoordeling. Onder deze status zal het niet meer mogelijk zijn om de etymologie te bewerken.');
		$this->modalSubmitActionLabel('Insturen');
		$this->modalCancelAction(false);
		
		$this->successNotificationTitle('De etymologie is ingestuurd ter beoordeling');
		$this->failureNotificationTitle('Helaas pindakaas! Er is iets misgelopen.');
		
		$this->action(function (): void {
			if ($this->process(fn (): bool => $this->record->state()->transitionToUnderReview())) {
				$this->success();
				return;
			}
			
			$this->failure();
		});
	}
}