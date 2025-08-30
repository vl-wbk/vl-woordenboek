<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\DisclaimerResource\Schema;

use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final readonly class InfolistSchema
{
	public static function configure(Infolist $infolist): Infolist
	{
		return $infolist
			->columns(12)
			->schema([
				Tabs::make('Information tabs')
					->columnSpan(12)
					->schema([
						Tab::make('Disclaimer informatie')
							->icon('heroicon-o-chat-bubble-bottom-center-text')
							->columns(12)
							->schema([
								TextEntry::make('type')
									->badge()
									->columnSpan(4)
									->label('Disclaimer type'),
								TextEntry::make('name')
									->columnSpan(8)
									->label('Naam van de disclaimer'),
								TextEntry::make('message')
									->label('Melding')
									->columnSpanFull(),
							]),
						Tab::make('Interne beschrijving')
							->columns(12)
							->icon('heroicon-o-document-text')
							->schema([
								TextEntry::make('description')
									->columnSpan(12)
									->hiddenLabel(),
							]),
						Tab::make('Gebruiksrichtlijn')
							->columns(12)
							->icon('heroicon-o-information-circle')
							->schema([
								TextEntry::make('usage')
									->hiddenLabel()
									->columnSpan(12),
							]),
					]),
			]);
		
	}
}