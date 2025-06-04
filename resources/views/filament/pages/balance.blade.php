<x-filament-panels::page>
    <div class="space-y-6">

        <div class="grid">
            <x-filament::card>
                <div class="text-gray-500">Текущий баланс</div>
                <div class="text-3xl font-bold text-green-600">₽ {{ number_format(auth()->user()->balance, 2) }}</div>

                <div style="margin-top: 20px!important">

                    <x-filament::modal>
                        <x-slot name="trigger">
                            <x-filament::button>
                                Пополнить баланс
                            </x-filament::button>
                        </x-slot>
                        <x-filament::input.wrapper>
                            <x-filament::input
                                type="number"
                                 min="0"
                                 wire:model="price"
                                 oninput="this.value = this.value.replace(/[^0-9.]/g, '')"
                            />
                        </x-filament::input.wrapper>
                        <x-filament::button wire:click="topUpBalance">
                            Пополнить баланс
                        </x-filament::button>
                    </x-filament::modal>

                </div>

            </x-filament::card>

        </div>

        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4">История транзакций</h3>

           <table class="min-w-full divide-y divide-gray-200 shadow-sm border rounded-lg overflow-hidden">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Описание</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сумма</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">

       @foreach (auth()->user()->balanceTransactions
            ->where('status', true)
            ->sortByDesc('created_at')
                as $balanceTransaction)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $balanceTransaction->created_at->format('Y-m-d H:i') }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $balanceTransaction->description }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-green-600 font-semibold">
                     @if ($balanceTransaction->type == 'increment')
                         +
                    @else
                         -
                     @endif
                    ₽{{ number_format($balanceTransaction->price, 2, '.', ' ') }}
                </td>
            </tr>
        @endforeach

    </tbody>
</table>

        </div>
    </div>
</x-filament-panels::page>

