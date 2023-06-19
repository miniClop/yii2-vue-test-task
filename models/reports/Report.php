<?php


namespace app\models\reports;

use Exception;
use app\services\ReportsServices;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;
use Yii;
use yii\web\UploadedFile;

abstract class Report implements IReport
{
    /** @var string Имя файла отчета */
    protected string $reportFileName;
    /** @var string Mime type файла отчета (По умолчанию XLSX) */
    protected string $reportMimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

    /**
     * Создание и сохранение отчета
     *
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generateReport(): Report
    {
        $this->prepareData();
        $this->saveSpreadsheetReportFile(new WriterXlsx($this->reportTableBuilder()));

        return $this;
    }

    /**
     * Скачивание файла отчета
     *
     * @return string
     * @throws Exception
     */
    public function downloadReport(): string
    {
        $path = $this->getPathToReport();
        if (is_null($path)) {
            throw new Exception('Не удалось получить файл отчета');
        }
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        return 'data:' . $this->reportMimeType . '/' . $type . ';base64,' . base64_encode($data);
    }

    /**
     * Сохранение электронных таблиц
     *
     * @param IWriter $writerClass
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    private function saveSpreadsheetReportFile(IWriter $writerClass): void
    {
        $writerClass->save($this->getFilePath($this->reportFileName));
    }

    /**
     * Получение электронной таблицы
     *
     * @param IReader $readerClass
     * @param string $filePath
     * @return Spreadsheet
     */
    protected function getSpreadsheetTable(IReader $readerClass, string $filePath): Spreadsheet
    {
        $reader = $readerClass->setReadDataOnly(true);
        return $reader->load($filePath);
    }


    /**
     * Проверка на пустоту записи в таблице
     *
     * @param $value
     * @return bool
     */
    protected function checkEmptyRowValue($value): bool
    {
        return is_null($value) || $value === '' || $value === 'X';
    }


    /**
     * Получение пути файла
     *
     * @param string $fileName
     * @return string
     */
    protected function getFilePath(string $fileName): string
    {
        return Yii::getAlias(ReportsServices::FILE_SAVE_ALIAS) . $fileName;
    }

    /**
     * Загрузка и сохранение файла
     *
     * @param string $respName
     * @param string $filepath
     * @return bool
     * @throws \Exception
     */
    protected function uploadedAndSaveFile(string $respName, string $filepath): bool
    {
        $uploadFiles = UploadedFile::getInstancesByName($respName);
        if (empty($uploadFiles)) {
            throw new Exception('Необходимо загрузить файл');
        }

        $file = array_shift($uploadFiles);
        return $file->saveAs($filepath);
    }

    /**
     * Получения пути файла отчета
     *
     * @return string|null
     */
    private function getPathToReport(): ?string
    {
        $path = $this->getFilePath($this->reportFileName);
        if (!file_exists($path)) {
            return null;
        }

        return $path;
    }
}