<?php

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VolunteerApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalHeading('Aanmelding bekjken')
                    ->modalWidth(Width::SevenExtraLarge)
                    ->modalCancelAction(false)
                    ->extraModalFooterActions([
                        DeleteAction::make()->extraAttributes(['class' => 'ms-auto']),
                        EditAction::make(),
                    ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
