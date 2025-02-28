<?php

namespace App\Services\Common;

use App\Repositories\ReportRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BaseExportService
{
    public BaseClientService $baseClientService;
    public ReportRepository $reportRepository;

    public function __construct(
        BaseClientService $baseClientService,
        ReportRepository $reportRepository,
    ) {
        $this->baseClientService = $baseClientService;
        $this->reportRepository = $reportRepository;
    }

    public function exportToExcel(array $data, string $filename = 'export.xlsx'): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = array_keys($data[0]);
        $sheet->fromArray([$headers], null, 'A1');

        foreach ($data as $rowIndex => $rowData) {
            $cellIndex = $rowIndex + 2;
            $values = array_values($rowData);

            foreach ($values as $columnIndex => $value) {
                $cellLetter = chr(65 + $columnIndex);
                $sheet->setCellValue("{$cellLetter}{$cellIndex}", $value);
            }
        }
        foreach ($headers as $headerIndex => $header) {
            $letter = chr(65 + $headerIndex);
            $sheet->getColumnDimension($letter)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $directory = storage_path('app/public/reports/');

        $filePath = $directory . '/' . $filename;
        $writer->save($filePath);
        return $filePath;
    }

    public function prepareAppealsArray(array $appeals): array
    {
        $exportArray = [];
        foreach ($appeals as $appeal) {
            $exportArray[] = [
                'ID сообщения' => $appeal->messageId,
                'Текст' => $appeal->text,
                'Источник сообщения' => $appeal->chat,
                'Тип чата' => $appeal->channelType,
                'Пользователь' => $this->baseClientService->getClientById($appeal->clientId)->getFullName(),
                'Дата сообщения' => $appeal->date,
            ];
        }

        return $exportArray;
    }

    public function createReport(array $data)
    {
        return $this->reportRepository->create($data);
    }
}
