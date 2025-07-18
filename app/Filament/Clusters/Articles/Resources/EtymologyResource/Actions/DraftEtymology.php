<?php

declare(strict_types=1);
	
namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Actions;

use App\Enums\Articles\EtymologyStatus;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Actions\Action;

/**
 * @property \App\Models\Etymology $record
 */
final class DraftEtymology extends Action
{
	use CanCustomizeProcess;
	
	public static function getDefaultName(): ?string
	{
		return EtymologyStatus::Draft->getLabel();
	}
	
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->authorize('draft', $this->record);
		
		$this->icon('heroicon-o-pencil-square');
		$this->color('warning');
		
		$this->requiresConfirmation();
		
		$this->modalIcon('heroicon-o-pencil-square');
		$this->modalCloseButton(false);
		$this->modalHeading('Gegevens in onderhoud plaatsen');
		$this->modalDescription('U staat op het punt om de etymologische gegevens in onderhoud te plaatsen. In deze fase zullen de gegevens niet publiekelijk raadpleegbaar zijn. Bent u zeker dat u dit wilt doen?');
		$this->modalSubmitActionLabel('Ja, ik ben zeker');
		
		$this->successNotificationTitle('De etymologische gegevens zijn nu in onderhoud');
		$this->failureNotificationTitle('Helaas pindakaas! Er is iets misgelopen.');
		
		$this->action(function(): void {
			if ($this->process(fn (array $data): bool => $this->record->state()->transitionToDraft())) {
				$this->success();
				return;
			}
			
			$this->failure();
		});
	}
}