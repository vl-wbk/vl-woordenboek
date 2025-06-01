<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources;

use App\Filament\Clusters\Blog;
use App\Filament\Clusters\Blog\Resources\BlogResource\Pages;
use App\Filament\Clusters\Blog\Resources\BlogResource\RelationManagers;
use App\Models\Blog as BlogPosts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

final class BlogResource extends Resource
{
    protected static ?string $model = BlogPosts::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Artikelen';

    protected static ?string $pluralModelLabel = 'Artikelen';

    protected static ?string $modelLabel = 'Artikel';

    protected static ?string $cluster = Blog::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Artikelen overzicht')
            ->description('Het Vlaams woordenboek is een levend wezen. Dat volop groeit en evolueerd naar de behoeftes en noden van gebruikers. Maar soms is het intressant om inzichten te geven in taalkundige kwesties en of de evolutie van het Woordenboek. Daarom kunt hier artikelen aanmaken en publiceren om gebruikers op de hoogte te houden.')
            ->headerActions([
                Action::make('help')
                    ->color('gray')
                    ->icon('heroicon-o-lifebuoy'),
                CreateAction::make('artikel aanmaken')
                    ->icon('heroicon-o-document-plus'),
            ])
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen artikelen gevonden of aangemaakt')
            ->emptyStateDescription('Het lijkt erop dat er momenteel nog geen artikelen zijn aangemaakt of gevonden met opgegeven criteria. Maak een artikel aan of kom later nog eens terug.')
            ->columns([
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Auteur')
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
