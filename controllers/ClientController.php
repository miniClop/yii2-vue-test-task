<?php

namespace app\controllers;

use app\models\Client;
use app\services\ReportsServices;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

class ClientController extends ActiveController
{
    public $modelClass = Client::class;
    /** @var string Сценарий для модели ( actions: create, update ) */
    private string $modelClassScenario = Client::SCENARIO_API_ACTIVE_REST;

    public function actions(): array
    {
        $actions = parent::actions();
        $actions['index'] = [
            'class' => 'yii\rest\IndexAction',
            'modelClass' => $this->modelClass,
            'prepareDataProvider' => fn() => new ActiveDataProvider(
                [
                    'query' => Client::find(),
                    'pagination' => false,
                ]
            ),
        ];

        foreach (['create', 'update'] as $action) {
            $actions[$action]['scenario'] = $this->modelClassScenario;
        }

        return $actions;
    }

    /**
     * Генерация новых записей
     *
     * @return bool
     */
    public function actionGenerateRecords(): bool
    {
        return Client::generateAndWriteRecords();
    }

    /**
     * Экспорт документа XLSX
     *
     * @return string
     */
    public function actionExportDocument(): string
    {
        return ReportsServices::clientReport();
    }
}
