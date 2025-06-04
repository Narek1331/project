<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdminParamResource\Pages;
use App\Filament\Admin\Resources\AdminParamResource\RelationManagers;
use App\Models\AdminParam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{
    TextInput,
    Card
};
class AdminParamResource extends Resource
{
    protected static ?string $model = AdminParam::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

     protected static ?string $title = 'Параметры';

    // protected static ?string $navigationGroup = '';

    protected static ?string $navigationLabel = 'Параметры';

    protected static ?string $pluralLabel = 'Параметры';

    protected static ?string $navigationLabelName = 'Параметры';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('one_click_price')
                        ->label('Цена за один клик')
                        ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
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
            'index' => Pages\ListAdminParams::route('/'),
            'edit' => Pages\EditAdminParam::route('/{record}/edit'),
        ];
    }
}
