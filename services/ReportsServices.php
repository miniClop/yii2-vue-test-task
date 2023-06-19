<?php

namespace app\services;

use app\models\reports\ClientReport;
use Exception;

class ReportsServices
{
    /** @var string Алиас сохранения файлов */
    public const FILE_SAVE_ALIAS = '@reportsStorage/';

    /**
     * Создание и сохранение отчета по клиентам
     *
     * @param int $countRecords
     * @return string
     */
    public static function clientReport(int $countRecords = 5): string
    {
        try {
            $report = (new ClientReport($countRecords))
                ->generateReport();

            return $report->downloadReport();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}