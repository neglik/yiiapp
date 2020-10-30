<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dish}}`.
 */
class m201021_231256_create_dish_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%dish}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull()->unique(),
            ],
            $tableOptions
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dish}}');
    }
}
