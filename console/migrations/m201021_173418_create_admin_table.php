<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin}}`.
 */
class m201021_173418_create_admin_table extends Migration
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
            '{{%admin}}',
            [
                'id' => $this->primaryKey(),
                'username' => $this->string()->notNull()->unique(),
                'auth_key' => $this->string(32)->notNull(),
                'password_hash' => $this->string()->notNull(),
                'email' => $this->string()->notNull()->unique(),
                'status' => $this->smallInteger()->notNull()->defaultValue(10),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
            ],
            $tableOptions
        );

        // Добавляем начального админстратора с именем 'admin' и паролем 'admin123'
        $this->insert(
            '{{%admin}}',
            [
                'username' => 'admin',
                'auth_key' => '4WzD6MT9TLQ_zZbWvmSxIP2437sC5VeW',
                'password_hash' => '$2y$13$nKKzgiPfP.huZEW24difVe7Lnyg60rx4J8WF9TqYqro3PtJ1IC6dS',
                'email' => 'noreply@mail.ru',
                'status' => 10,
                'created_at' => time(),
                'updated_at' => time(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin}}');
    }
}
