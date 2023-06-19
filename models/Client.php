<?php

namespace app\models;

use DateTime;
use Faker\Factory;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sds_client".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $phone
 * @property string|null $email
 * @property int|null $bidth
 */
class Client extends \yii\db\ActiveRecord
{
    /** @var string Сценарий ACTIVE API REST */
    public const SCENARIO_API_ACTIVE_REST = 'api_active_rest';
    /** @var string Формат вывода даты */
    private const DATE_FORMAT = 'Y-m-d';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%client}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['birthDay'], 'string', 'on' => self::SCENARIO_API_ACTIVE_REST],
            [['birthDay'], 'required', 'on' => self::SCENARIO_API_ACTIVE_REST],
            [['bidth'], 'integer'],
            [['name', 'email', 'phone'], 'string', 'max' => 255],
            [['phone'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'email' => 'Email',
            'bidth' => 'Дата рождения',
        ];
    }

    public function fields(): array
    {
        $parentFields = parent::fields();

        return ArrayHelper::merge($parentFields, [
            'birthDay' => fn(self $m): string => $m->getBirthDay()
        ]);
    }

    public function load($data, $formName = null): bool
    {
        switch ($this->getScenario()) {
            case $this::SCENARIO_API_ACTIVE_REST:
                //TODO:Вынести в DTO, а лучше если на фронте (ClientForm.vue) оперировать сразу timestamp
                $bData = $data;
                $bData['bidth'] = (DateTime::createFromFormat($this::DATE_FORMAT, $bData['birthDay']))->getTimestamp();
                unset($bData['birthDay']);

                $this->setAttributes($bData);
                return true;
            default:
                return parent::load($data, $formName);
        }
    }

    /**
     * Получение даты рождения
     *
     * @param string|null $dateFormat
     * @return string
     */
    public function getBirthDay(string $dateFormat = null): string
    {
        return date($dateFormat ?? $this::DATE_FORMAT, $this->bidth);
    }

    /**
     * Создание и сохранение новых записей
     *
     * @param int $count
     * @return bool
     */
    public static function generateAndWriteRecords(int $count = 1000): bool
    {
        $faker = Factory::create('ru_RU');
        for ($i = 0; $i < $count; $i++) {
            $model = new self();

            $model->name = $faker->name();
            $model->phone = $faker->phoneNumber();
            $model->email = $faker->email();
            $model->bidth = $faker->dateTimeBetween()->getTimestamp();

            //TODO: При необходимости вставить вывод ошибки и количество созданных записей
            if (!$model->save()) {
                return false;
            }
        }

        return true;
    }
}
