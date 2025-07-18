<?php

declare(strict_types=1);
	
namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Actions;
	
use App\Enums\Articles\EtymologyStatus;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Textarea;
	
/**
 * Class RejectEtymology
 *
 * This action handles the rejection of an etymology record. It enforces the
 * 'reject' authorization policy, displays a confirmation modal with a required
 * textarea for the rejection reason, and uses a danger style with a thumb-down icon
 * to signal a destructive operation. Upon submission it attempts the state transition
 * to Rejected on the model and shows either a success or failure notification
 * depending on the outcome.
 *
 * @property \App\Models\Etymology $record THe etymology instance to be rejected.
 */
final class RejectEtymology extends Action
{
	use CanCustomizeProcess;
	
	/**
	 * Provide the default name of this action by returning the localized label of the Rejected enum value.
	 * This label is used as the button text.
	 *
	 * @return string|null
	 */
	public static function getDefaultName(): ?string
	{
		return EtymologyStatus::Rejected->getLabel();
	}
	
	/**
	 * Configure the visual appearance, confirmation dialog, form fields, notification titles, and execution logic.
	 * This includes setting the icon, color, modal heading, and description, and defining a callback that processes
	 * the rejection reason and invokes the model state transition.
	 *
	 * @return void
	 */
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->authorize('reject', $this->record);
		
		$this->icon('heroicon-o-hand-thumb-down');
		$this->color('danger');
		
		$this->requiresConfirmation();
			
		$this->modalIcon('heroicon-o-hand-thumb-down');
		$this->modalCloseButton(false);
		$this->modalHeading('Etymology afwijzen');
		$this->modalDescription('U staat op het punt om een etymology af te wijzen in het systeem. Bij afwijzing zal deze niet gepubliceerd worden. Bent u zeker dat u dit wilt doen?');
		$this->modalSubmitActionLabel('Ja, ik ben zeker');
			
		$this->form([
			Textarea::make('reason')
				->label('Reden van de archivering')
				->placeholder('Beschrijf kort waarom je de gegevens wilt archiveren.')
				->rows(5)
				->required()
		]);
			
		$this->successNotificationTitle('De etymologische gegevens of bijdragen zijn afgewezen');
		$this->failureNotificationTitle('Helaas pindakaas! Er is iets misgelopen.');
			
		$this->action(function (): void {
			if ($this->process(fn (array $data): bool => $this->record->state()->transitionToRejected($data['reason']))) {
				$this->success();
				return;
			}
				
			$this->failure();
		});
	}
}