<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class GoogleSheetsService
{
    protected Sheets $service;
    protected string $spreadsheetId;
    protected string $sheetName = 'Sheet1';

    public function __construct()
    {
        $client = new Client();
        $client->setApplicationName('Laravel Google Sheets');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');

        $this->service = new Sheets($client);
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
    }

    public function insertMany(array $rows): void
    {
        $existingRows = $this->getAllRows();
        $columnCount = $this->getMaxColumnCount(array_merge($existingRows, $rows));

        foreach ($rows as $row) {
            $this->upsertRowByThirdColumn($row, $existingRows, $columnCount);
        }
    }

    protected function getAllRows(): array
    {
        $range = $this->sheetName . '!A1:Z'; // Максимум 26 колонок
        try {
            $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
            return $response->getValues() ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getMaxColumnCount(array $rows): int
    {
        $max = 0;
        foreach ($rows as $row) {
            $max = max($max, count($row));
        }
        return $max;
    }

    protected function upsertRowByThirdColumn(array $row, array &$existingRows, int $columnCount): void
    {
        $fullRow = array_pad($row, $columnCount, ''); // Заполняем пустыми ячейками

        foreach ($existingRows as $index => $existingRow) {
            if (isset($existingRow[2]) && $existingRow[2] === $row[2]) {
                $rowIndex = $index + 1;
                $range = $this->sheetName . '!A' . $rowIndex . ':' . $this->columnLetter($columnCount) . $rowIndex;

                $body = new ValueRange(['values' => [$fullRow]]);
                $params = ['valueInputOption' => 'USER_ENTERED'];

                $this->service->spreadsheets_values->update(
                    $this->spreadsheetId,
                    $range,
                    $body,
                    $params
                );

                // Обновляем локально, чтобы не вставлять повторно
                $existingRows[$index] = $fullRow;
                return;
            }
        }

        // Если не найдено — добавляем
        $this->appendRow($fullRow);
        $existingRows[] = $fullRow;
    }

    protected function appendRow(array $row): void
    {
        $range = $this->sheetName . '!A1';
        $body = new ValueRange(['values' => [$row]]);
        $params = ['valueInputOption' => 'USER_ENTERED'];

        $this->service->spreadsheets_values->append(
            $this->spreadsheetId,
            $range,
            $body,
            $params
        );
    }

    protected function columnLetter(int $index): string
    {
        $letters = '';
        while ($index > 0) {
            $index--;
            $letters = chr(65 + ($index % 26)) . $letters;
            $index = intdiv($index, 26);
        }
        return $letters;
    }

    public function deleteRowsByDomain(string $domain): void
    {
        $existingRows = $this->getAllRows();

        $batchUpdateRequests = [];
        foreach ($existingRows as $index => $row) {
            if (isset($row[0]) && trim($row[0]) === $domain) {
                // Запоминаем строку для удаления (нумерация с 0)
                $batchUpdateRequests[] = [
                    'deleteDimension' => [
                        'range' => [
                            'sheetId' => $this->getSheetIdByName($this->sheetName),
                            'dimension' => 'ROWS',
                            'startIndex' => $index,
                            'endIndex' => $index + 1,
                        ],
                    ],
                ];
            }
        }

        if (!empty($batchUpdateRequests)) {
            $this->service->spreadsheets->batchUpdate($this->spreadsheetId, new \Google\Service\Sheets\BatchUpdateSpreadsheetRequest([
                'requests' => array_reverse($batchUpdateRequests), // важно: удаляем с конца, чтобы индексы не сбились
            ]));
        }
    }

    protected function getSheetIdByName(string $sheetName): int
    {
        $spreadsheet = $this->service->spreadsheets->get($this->spreadsheetId);
        foreach ($spreadsheet->getSheets() as $sheet) {
            if ($sheet->getProperties()->getTitle() === $sheetName) {
                return $sheet->getProperties()->getSheetId();
            }
        }

        throw new \Exception("Sheet '$sheetName' not found.");
    }

}
