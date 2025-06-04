<?php

namespace App\Filament\Admin\Resources\AdminParamResource\Pages;

use App\Filament\Admin\Resources\AdminParamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Redirect;
use App\Models\AdminParam;

class ListAdminParams extends ListRecords
{
    protected static string $resource = AdminParamResource::class;

    public function mount(): void
    {
        if (AdminParam::count() > 0) {
            $firstRecordId = AdminParam::first()->id;
            Redirect::to(static::getResource()::getUrl('edit', ['record' => $firstRecordId]));
        }
    }
}
