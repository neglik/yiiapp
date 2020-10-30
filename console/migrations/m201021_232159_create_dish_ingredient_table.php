<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dish_ingredient}}`.
 */
class m201021_232159_create_dish_ingredient_table extends Migration
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
            '{{%dish_ingredient}}',
            [
                'id' => $this->primaryKey(),
                'dish_id' => $this->integer()->notNull(),
                'ingredient_id' => $this->integer()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex(
            'unq-dish_ingredient-dish_id-ingredient_id',
            '{{%dish_ingredient}}',
            [
                'dish_id',
                'ingredient_id',
            ],
            true
        );

        $this->addForeignKey(
            'fk-dish_ingredient-dish_id',
            '{{%dish_ingredient}}',
            'dish_id',
            '{{%dish}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-dish_ingredient-ingredient_id',
            '{{%dish_ingredient}}',
            'ingredient_id',
            '{{%ingredient}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'unq-dish_ingredient-dish_id-ingredient_id',
            '{{%dish_ingredient}}'
        );

        $this->dropForeignKey(
            'fk-dish_ingredient-dish_id',
            '{{%dish_ingredient}}'
        );

        $this->dropForeignKey(
            'fk-dish_ingredient-ingredient_id',
            '{{%dish_ingredient}}'
        );

        $this->dropTable('{{%dish_ingredient}}');
    }
}
