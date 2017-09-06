<?php

use yii\db\Migration;

/**
 * Class m170903_144240_init
 */
class m170903_144240_init extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(64),
            'email' => $this->string(64)->notNull()->unique(),
            'name' => $this->string(64),
            'password_hash' => $this->string(),
            'password_reset_token' => $this->string()->unique(),
            'created_at' => $this->bigInteger()->notNull(),
            'updated_at' => $this->bigInteger()->notNull(),
            'last_login' => $this->bigInteger(),
            'role_id' => $this->smallInteger(),
            'auth_key' => $this->string(32),
            'notification_type_id' => $this->smallInteger(),
            'confirmation_token' => $this->string(32)
        ]);

        $this->createIndex('idx_user_email', '{{%user}}', 'email');

        $this->createTable('{{%publication}}', [
            'id' => $this->primaryKey(),
            'image' => $this->string(),
            'annotation' => $this->string(256),
            'text' => $this->text(),
            'created_at' => $this->bigInteger()->notNull(),
            'updated_at' => $this->bigInteger()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'status_id' => $this->smallInteger(),
            'name' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%publication}}');

        $this->dropIndex('idx_user_email', '{{%user}}');

        $this->dropTable('{{%user}}');
    }
}
