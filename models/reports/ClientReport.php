<?php

namespace app\models\reports;

use app\models\Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientReport extends Report
{
    /** @var string Имя файла отчета */
    protected string $reportFileName = 'clientReport.xlsx';

    /** @var int Количество выборки */
    private int $countRows;
    /** @var Client[]  */
    private array $clientsData = [];

    public function __construct(int $countReportRows)
    {
        $this->countRows = $countReportRows;
    }

    public function prepareData(): void
    {
        $this->clientsData = array_map(fn(Client $c): Client => $c,
            Client::find()->orderBy(['bidth' => SORT_ASC])->limit($this->countRows)->all()
        );
    }

    public function reportTableBuilder(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->fillMarkup($sheet);

        $columnIndex = 1;
        $row = 2;
        foreach ($this->clientsData as $model) {
            $sheet->setCellValue([$columnIndex++, $row], $model->name);
            $sheet->setCellValue([$columnIndex++, $row], $model->phone);
            $sheet->setCellValue([$columnIndex++, $row], $model->email);
            $sheet->setCellValue([$columnIndex, $row], $model->getBirthDay());

            $columnIndex = 1;
            $row++;
        }

        return $spreadsheet;
    }

    /**
     * Заполнение разметки
     *
     * @param Worksheet $worksheet
     */
    private function fillMarkup(Worksheet $worksheet): void
    {
        $columnIndex = 1;
        foreach (array_slice((new Client)->attributeLabels(), 1) as $label) {
            $worksheet->setCellValue([$columnIndex++, 1], $label);
        }
    }
}