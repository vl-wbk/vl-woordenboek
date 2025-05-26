<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Filament\Resources\FeedbackResource\RelationManagers;
use App\Models\Feedback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

final class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Ingezonden feedback')
            ->description('Een overzicht van alle feedback of bugs die zijn ingezonden door gebruikers van het Vlaams Woordenboek')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen feedback ontvangen')
            ->emptyStateDescription('Momenteel is er nog geen feedback ingestuurd door gebruikers van het Vlmaams woordenboek. Kom later nog eens terug.')
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedback::route('/'),
        ];
    }
}
