<?php

namespace App\Filament\Admin\Resources\AdminParamResource\Pages;

use App\Filament\Admin\Resources\AdminParamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminParam extends EditRecord
{
    protected static string $resource = AdminParamResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return 'Параметры';
    }
}
