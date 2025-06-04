<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Resources\SiteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Imports\SitesImport;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
class ListSites extends ListRecords
{
    protected static string $resource = SiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('importExcel')
    ->label('Импорт Excel')
    ->icon('heroicon-o-arrow-up-on-square')
    ->form([
        Forms\Components\FileUpload::make('file')
            ->label('Файл Excel (.xlsx)')
            ->required()
            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
            ->directory('imports')
            ->disk('local'),
    ])
    ->action(function (array $data) {
        $filePath = Storage::disk('local')->path($data['file']);

        try {
            Excel::import(new \App\Imports\SitesImport, $filePath);

            Notification::make()
                ->title('Импорт завершён')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Ошибка импорта')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    })
    ->modalHeading('Импорт сайтов из Excel'),
        ];
    }
}
