<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\UserBalanceService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Redirect;
class Balance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $title = 'Баланс';
    protected static string $view = 'filament.pages.balance';

    public function __construct()
    {
        $this->userBalanceService = app(UserBalanceService::class);
    }
    public int $price = 0;

    public function topUpBalance()
    {
        $this->userBalanceService->topUp($this->price);

         Notification::make()
            ->title('Баланс пополнен')
            ->body("Вы успешно пополнили баланс на {$this->price}₽.")
            ->success()
            ->send();

        $this->price = 0;

        $this->redirect('/app/balance');

    }

}
