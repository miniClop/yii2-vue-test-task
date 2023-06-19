<?php

use Faker\Factory;
use yii\db\Migration;

/**
 * Class m211006_115439_seed_client_table
 */
class m211006_115439_seed_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insertFakeBooks();
    }

    private function insertFakeBooks()
    {
        $faker = Factory::create('ru_RU');
        for ($i = 0; $i < 10; $i++) {
            $this->insert(
                '{{%client}}',
                [
                    'name' => $faker->name(),
                    'phone' => $faker->phoneNumber(),
                    'email' => $faker->email(),
                    'bidth' => $faker->dateTimeBetween()->getTimestamp()
                ]
            );
        }
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211006_115439_seed_book_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211006_115439_seed_book_table cannot be reverted.\n";

        return false;
    }
    */
}
