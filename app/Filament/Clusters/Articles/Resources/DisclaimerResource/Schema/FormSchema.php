<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\DisclaimerResource\Schema;

use App\Enums\DisclaimerTypes;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Support\Enums\IconSize;

final readonly class FormSchema
{
	private static function createSection(string $title, string $icon, string $description): Section
	{
		return Section::make($title)
			->icon($icon)
			->iconSize(IconSize::Medium)
			->iconColor('primary')
			->description($description)
			->collapsible()
			->columns(12)
			->compact();
	}
	
	public static function configure(Form $form): Form
	{
		return $form
			->columns(12)
			->schema([
				self::createSection(
					title: 'Disclaimer informatie',
					icon: 'heroicon-o-wrench-screwdriver',
					description:'Alle gegevens en configuratie die gebruikt zal worden om de disclaimer te tonen aan de eindgebruiker die het Vlaams woordenboek raadpleegt.'
				)->schema(self::getDisclaimerInformationSchema()),
				
				self::createSection(
					title: 'Beheersinformatie',
					icon: 'heroicon-o-information-circle',
					description: 'De nodige registraties van interne gegevens die ons toelaat de disclaimers te beheren en te vermelden hoe we de geregistreerde disclaimer wensen te gebruiken.'
				)->schema(self::getManagementInformationSchema()),
			]);
	}
	
	private static function getDisclaimerInformationSchema(): array
	{
		return [
			Select::make('type')
				->columnSpan(6)
				->required()
				->options(DisclaimerTypes::class)
				->native(false),
			Textarea::make('message')
				->label('Disclaimer melding')
				->required()
				->placeholder('Vermeld kort wat je wenst te vermelding richting de gebruiker')
				->columnSpan(12)
				->rows(2),
		];
	}
	
	private static function getManagementInformationSchema(): array
	{
		return [
			TextInput::make('name')
				->label('Naam')
				->maxLength(255)
				->required()
				->unique(ignoreRecord: true)
				->columnSpan(8),
			Textarea::make('description')
				->label('Beschrijving')
				->required()
				->placeholder('Beschrijf kort waarover de disclaimer gaat zodat het duidelijk is voor andere vrijwilligers')
				->columnSpan(12)
				->rows(3),
			Textarea::make('usage')
				->label('Gebruikscriteria')
				->required()
				->placeholder('Beschrijf kort in welke omstandigheden de disclaimer te gebruiken is')
				->columnSpan(12)
				->rows(3),
		];
	}
}