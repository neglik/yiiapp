<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ingredient}}`.
 */
class m201021_231226_create_ingredient_table extends Migration
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
            '{{%ingredient}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull()->unique(),
                'is_visible' => $this->boolean()->defaultValue(true),
            ],
            $tableOptions
        );

        $this->createIndex(
            'idx-ingredient-is_visible',
            '{{%ingredient}}',
            'is_visible'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-ingredient-is_visible',
            '{{%ingredient}}'
        );

        $this->dropTable('{{%ingredient}}');
    }
}
