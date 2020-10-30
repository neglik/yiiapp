<?php

use yii\db\Migration;

/**
 * Class m201027_194819_add_column_hidden_components_counter_to_dish_table
 */
class m201027_194819_add_column_hidden_components_counter_to_dish_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%dish}}',
            'hidden_components_counter',
            $this->integer()->notNull()->defaultValue(0)
        );

        $this->createIndex(
            'idx-dish-hidden_components_counter',
            '{{%dish}}',
            'hidden_components_counter'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-dish-hidden_components_counter',
            '{{%dish}}'
        );

        $this->dropColumn(
            '{{%dish}}',
            'hidden_components_counter'
        );
    }
}
