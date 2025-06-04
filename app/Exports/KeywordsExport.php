<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KeywordsExport implements FromCollection, WithHeadings
{
    protected $record;

    public function __construct($record)
    {
        $this->record = $record;
    }

    public function collection()
    {
        return collect([
            [
                $this->record->domain ?? '',
                '',
                $this->record->keywords->pluck('name')->implode(', ') ?? '',
                $this->record->keywords->sum('clicks_per_day'),
                $this->record->region ?? '',
                'Яндекс'
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            '',
            '',
            'ключевой запрос',
            'кол-во кликов в сутки',
            'Регион',
            'Яндекс'
        ];
    }
}
