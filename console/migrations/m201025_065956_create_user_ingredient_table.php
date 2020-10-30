<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_ingredient}}`.
 */
class m201025_065956_create_user_ingredient_table extends Migration
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
            '{{%user_ingredient}}',
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer()->notNull(),
                'ingredient_id' => $this->integer()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex(
            'unq-user_ingredient-user_id-ingredient_id',
            '{{%user_ingredient}}',
            [
                'user_id',
                'ingredient_id',
            ],
            true
        );

        $this->addForeignKey(
            'fk-user_ingredient-user_id',
            '{{%user_ingredient}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_ingredient-ingredient_id',
            '{{%user_ingredient}}',
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
            'unq-user_ingredient-user_id-ingredient_id',
            '{{%user_ingredient}}'
        );

        $this->dropForeignKey(
            'fk-user_ingredient-user_id',
            '{{%user_ingredient}}'
        );

        $this->dropForeignKey(
            'fk-user_ingredient-user_id',
            '{{%user_ingredient}}'
        );

        $this->dropTable('{{%user_ingredient}}');
    }
}
