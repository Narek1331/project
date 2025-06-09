<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteResource\Pages;
use App\Filament\Resources\SiteResource\RelationManagers;
use App\Models\{
    Site,
    AdminParam
};
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\{
    TextColumn,
};
use Filament\Forms\Components\{
    TextInput,
    Card,
    Select,
    Repeater
};
use App\Forms\Components\NumericInput;
use App\Exports\KeywordsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\GoogleSheetsService;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

     protected static ?string $title = 'Сайты';

    // protected static ?string $navigationGroup = '';

    protected static ?string $navigationLabel = 'Сайты';

    protected static ?string $pluralLabel = 'Сайты';

    protected static ?string $navigationLabelName = 'Сайты';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('domain')
                        ->label('Домен')
                        ->required()
                        ->rules([
                            'regex:/^(?!(https?:\/\/|www\.))[\w.-]+\.[a-z]{2,}$/i',
                        ])
                        ->validationMessages([
                            'regex' => 'Поле не должно начинаться с http://, https:// или www.',
                        ])
                        ->helperText('Введите домен без http, https и www. Например: example.com'),

                    Repeater::make('keywords')
                        ->relationship('keywords')
                        ->label('Ключевые слова')
                        ->schema([
                            TextInput::make('name')
                                ->label('Название')
                                ->required(),
                            TextInput::make('url')
                                ->label('url')
                                 ->rules([
                                    'regex:/^(?!(https?:\/\/|www\.))[\w.-]+\.[a-z]{2,}(\/[\w\-\/]*)?$/i',
                                    'not_regex:/\/$/',
                                ])
                                ->validationMessages([
                                    'regex' => 'Поле не должно начинаться с http://, https:// или www.',
                                    'not_regex' => 'URL не должен заканчиваться на /',
                                ]),
                            NumericInput::make('clicks_per_day')
                                ->label('Кликов в сутки')
                                ->default(1)
                                ->minValue(0)
                        ])
                        ->columns(3)
                        ->addActionLabel('Добавить ключевые слова'),
                    Select::make('region')
                        ->label('Регион')
                        ->options([
                            'Республики' => [
                                'Республика Адыгея' => 'Республика Адыгея',
                                'Республика Алтай' => 'Республика Алтай',
                                'Республика Башкортостан' => 'Республика Башкортостан',
                                'Республика Бурятия' => 'Республика Бурятия',
                                'Республика Дагестан' => 'Республика Дагестан',
                                'Республика Ингушетия' => 'Республика Ингушетия',
                                'Кабардино-Балкарская Республика' => 'Кабардино-Балкарская Республика',
                                'Калмыкия' => 'Калмыкия',
                                'Карачаево-Черкесская Республика' => 'Карачаево-Черкесская Республика',
                                'Республика Карелия' => 'Республика Карелия',
                                'Республика Коми' => 'Республика Коми',
                                'Республика Марий Эл' => 'Республика Марий Эл',
                                'Республика Мордовия' => 'Республика Мордовия',
                                'Республика Саха (Якутия)' => 'Республика Саха (Якутия)',
                                'Республика Северная Осетия — Алания' => 'Республика Северная Осетия — Алания',
                                'Республика Татарстан' => 'Республика Татарстан',
                                'Республика Тыва' => 'Республика Тыва',
                                'Удмуртская Республика' => 'Удмуртская Республика',
                                'Республика Хакасия' => 'Республика Хакасия',
                                'Чеченская Республика' => 'Чеченская Республика',
                                'Чувашская Республика' => 'Чувашская Республика',
                            ],
                            'Края' => [
                                'Алтайский край' => 'Алтайский край',
                                'Забайкальский край' => 'Забайкальский край',
                                'Камчатский край' => 'Камчатский край',
                                'Краснодарский край' => 'Краснодарский край',
                                'Красноярский край' => 'Красноярский край',
                                'Пермский край' => 'Пермский край',
                                'Приморский край' => 'Приморский край',
                                'Ставропольский край' => 'Ставропольский край',
                                'Хабаровский край' => 'Хабаровский край',
                            ],
                            'Области' => [
                                'Амурская область' => 'Амурская область',
                                'Архангельская область' => 'Архангельская область',
                                'Астраханская область' => 'Астраханская область',
                                'Белгородская область' => 'Белгородская область',
                                'Брянская область' => 'Брянская область',
                                'Владимирская область' => 'Владимирская область',
                                'Волгоградская область' => 'Волгоградская область',
                                'Вологодская область' => 'Вологодская область',
                                'Воронежская область' => 'Воронежская область',
                                'Ивановская область' => 'Ивановская область',
                                'Иркутская область' => 'Иркутская область',
                                'Калининградская область' => 'Калининградская область',
                                'Калужская область' => 'Калужская область',
                                'Кемеровская область' => 'Кемеровская область',
                                'Кировская область' => 'Кировская область',
                                'Костромская область' => 'Костромская область',
                                'Курганская область' => 'Курганская область',
                                'Курская область' => 'Курская область',
                                'Ленинградская область' => 'Ленинградская область',
                                'Липецкая область' => 'Липецкая область',
                                'Магаданская область' => 'Магаданская область',
                                'Московская область' => 'Московская область',
                                'Мурманская область' => 'Мурманская область',
                                'Нижегородская область' => 'Нижегородская область',
                                'Новгородская область' => 'Новгородская область',
                                'Новосибирская область' => 'Новосибирская область',
                                'Омская область' => 'Омская область',
                                'Оренбургская область' => 'Оренбургская область',
                                'Орловская область' => 'Орловская область',
                                'Пензенская область' => 'Пензенская область',
                                'Псковская область' => 'Псковская область',
                                'Ростовская область' => 'Ростовская область',
                                'Рязанская область' => 'Рязанская область',
                                'Самарская область' => 'Самарская область',
                                'Саратовская область' => 'Саратовская область',
                                'Сахалинская область' => 'Сахалинская область',
                                'Свердловская область' => 'Свердловская область',
                                'Смоленская область' => 'Смоленская область',
                                'Тамбовская область' => 'Тамбовская область',
                                'Тверская область' => 'Тверская область',
                                'Томская область' => 'Томская область',
                                'Тульская область' => 'Тульская область',
                                'Тюменская область' => 'Тюменская область',
                                'Ульяновская область' => 'Ульяновская область',
                                'Челябинская область' => 'Челябинская область',
                                'Ярославская область' => 'Ярославская область',
                            ],
                            'Города федерального значения' => [
                                'Москва' => 'Москва',
                                'Санкт-Петербург' => 'Санкт-Петербург',
                                'Севастополь' => 'Севастополь',
                            ],
                            'Автономные округа' => [
                                'Чукотский автономный округ' => 'Чукотский автономный округ',
                                'Ханты-Мансийский автономный округ — Югра' => 'Ханты-Мансийский автономный округ — Югра',
                                'Ненецкий автономный округ' => 'Ненецкий автономный округ',
                                'Ямало-Ненецкий автономный округ' => 'Ямало-Ненецкий автономный округ',
                            ],
                            'Автономная область' => [
                                'Еврейская автономная область' => 'Еврейская автономная область',
                            ],
                        ])
                        ->searchable()
                        ->required()


                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        $adminParam = AdminParam::first();

        return $table
            ->columns([
                TextColumn::make('domain')
                    ->label('URL')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('keywords_count')
                    ->label('Ключевых слов')
                    ->getStateUsing(function($record){
                        return $record->keywords->count();
                    }),
                TextColumn::make('cost_per_day')
                    ->label('Стоимость в сутки')
                    ->getStateUsing(function($record) use($adminParam){
                        return $adminParam->one_click_price * $record->keywords->sum('clicks_per_day') . ' ₽';
                    }),
                TextColumn::make('click_in_hour')
                    ->label('Клики/Час'),
                TextColumn::make('click_per_day')
                    ->label('Клики/Сегодня')
            ])
            ->filters([
                //
            ])
            ->actions([
                 Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download_excel')
                    ->label('')
                    ->action(function ($record) {
                        $record->status = true;
                        $record->save();
                        //  $rows = [];

                        //  foreach($record->keywords as $keyword)
                        //  {
                        //     $rows[] = [
                        //         $record->domain,
                        //         $keyword->url ?? '',
                        //         $keyword->name,
                        //         $keyword->clicks_per_day,
                        //         $record->region,
                        //         'Яндекс'
                        //     ];
                        //  }

                        // app(GoogleSheetsService::class)->insertMany($rows);

                    })
                    ->icon('heroicon-o-play'),
                Tables\Actions\Action::make('pause')
                    ->label('')
                    ->icon('heroicon-o-pause')
                    ->color('danger')
                    ->action(function ($record) {

                        $record->status = false;
                        $record->save();

                        // app(GoogleSheetsService::class)->deleteRowsByDomain($record->domain);
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(
                fn ($record) => null,
            );
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
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }
}
