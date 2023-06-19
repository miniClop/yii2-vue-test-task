<?php


namespace app\models\reports;


use PhpOffice\PhpSpreadsheet\Spreadsheet;

interface IReport
{
    /**
     * Подготовка данных
     */
    public function prepareData(): void;

    /**
     * Создание таблицы отчета
     *
     * @return Spreadsheet
     */
    public function reportTableBuilder(): Spreadsheet;
}