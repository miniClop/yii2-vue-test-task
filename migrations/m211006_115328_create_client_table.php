<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client}}`.
 */
class m211006_115328_create_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%client}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'phone' => $this->string()->unique(),
            'email' => $this->string()->unique(),
            'bidth' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%client}}');
    }
}
