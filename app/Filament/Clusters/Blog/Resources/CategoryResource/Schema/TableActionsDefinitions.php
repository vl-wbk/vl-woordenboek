<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\CategoryResource\Schema;

use Filament\Tables\Actions;

final readonly class TableActionsDefinitions
{
    /**
     * @return array<int, Actions\BulkActionGroup>
     */
    public static function getBulkActions(): array
    {
        return [
            Actions\BulkActionGroup::make([
                Actions\DeleteBulkAction::make(),
            ]),
        ];
    }

    /**
     * @return array<int, Actions\ViewAction|Actions\EditAction|Actions\DeleteAction>
     */
    public static function getRowActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->hiddenLabel()
                ->tooltip('Bekijken')
                ->modalIcon('heroicon-o-information-circle')
                ->modalIconColor('info')
                ->modalHeading('Categorie informatie')
                ->modalDescription('Een overzicht van alle gegevens die behoren tot de categorie'),

            Actions\EditAction::make()
                ->hiddenLabel()
                ->tooltip('Bewerken')
                ->modalHeading('Categorie wijzigen')
                ->modalIcon('heroicon-o-pencil-square')
                ->modalDescription('Via het onderstaande formulier kunt de gegevens wijzigen van de categorie'),

            Actions\DeleteAction::make()
                ->hiddenLabel()
                ->tooltip('Verwijderen')
                ->modalDescription('Bij het verwijderen van de categorie zal deze automatisch verwijderd worden van bestaande nieuwsberichten. Weet je zeker dat je dit wilt doen?'),
        ];
    }

    /**
     * @return array<int, Actions\Action|Actions\CreateAction>
     */
    public static function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Help')
                ->color('gray')
                ->icon('heroicon-o-lifebuoy'),

            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label('categorie toevoegen')
                ->modalHeading('Nieuwe categorie aanmaken')
                ->modalIcon('heroicon-o-plus')
                ->modalDescription('Via het onderstaande formulier kunt u een nieuwe categorie aanmaken voor een nieuwsbericht'),
        ];
    }
}
