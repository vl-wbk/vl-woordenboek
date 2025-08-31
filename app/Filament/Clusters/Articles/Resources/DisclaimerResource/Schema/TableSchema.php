<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\DisclaimerResource\Schema;

use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables;

final readonly class TableSchema
{
	public static function configure(Table $table): Table
	{
		return $table
			->heading('Disclaimers')
			->description('Disclaimers zijn bedoeld om gebruikers snel extra informatie te geven.')
			->emptyStateIcon('heroicon-o-information-circle')
			->emptyStateHeading('Geen disclaimer(s) aangemaakt')
			->emptyStateDescription('Momenteel zijn er geen disclaimers aangemaakt en of gevonden onder de matchende de gegeven criteria.')
			->columns(components: self::configureColumnComponents())
			->actions(actions: self::configureActions())
			->headerActions(actions: self::configureHeaderActions())
			->bulkActions(actions: self::configureBulkActions());
	}
	
	private static function configureHeaderActions(): array
	{
		return [
			Action::make('help')
				->label('Help')
				->icon('heroicon-o-lifebuoy')
				->color('gray'),
			CreateAction::make()
				->label('Disclaimer aanmaken')
				->icon('heroicon-o-plus-circle'),
		];
	}
	
	private static function configureColumnComponents(): array
	{
		return [
			TextColumn::make('name')
				->label('naam')
				->sortable()
				->weight(FontWeight::SemiBold)
				->color('primary')
				->searchable(),
			TextColumn::make('articles_count')
				->counts('articles')
				->sortable()
				->label('aantal koppelingen'),
			TextColumn::make('description')
				->label('beschrijving')
				->words(12)
				->searchable(),
			TextColumn::make('created_at')
				->sortable()
				->label('aangemaakt op')
				->date(),
		];
	}
	
	private static function configureActions(): array
	{
		return [
			Tables\Actions\ViewAction::make()
				->hiddenLabel()
				->tooltip('bekijken'),
			Tables\Actions\EditAction::make()
				->hiddenLabel()
				->tooltip('bewerken'),
			Tables\Actions\DeleteAction::make()
				->modalDescription('U staat op het punt om een disclaimer te verwijderen. Bij het verwijderen zal deze worden losgekoppeld van alle artikelen. Weet u zeker dat je dit wilt doen?')
				->hiddenLabel()
				->tooltip('verwijderen'),
		];
	}
	
	private static function configureBulkActions(): array
	{
		return [
			Tables\Actions\BulkActionGroup::make([
				Tables\Actions\DeleteBulkAction::make(),
			]),
		];
	}
}