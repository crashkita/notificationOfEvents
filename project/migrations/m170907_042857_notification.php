<?php

use yii\db\Migration;

/**
 * Class m170907_042857_notification
 */
class m170907_042857_notification extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'publication_id' => $this->integer(),
            'created_at' => $this->bigInteger(),
            'updated_at' => $this->bigInteger(),
            'status_id' => $this->smallInteger(),
            'type_id' => $this->smallInteger()
        ]);

        $this->addForeignKey('fk_notification_user', '{{%notification}}', 'user_id', 'user', 'id');
        $this->addForeignKey('fk_notification_publication', '{{%notification}}', 'publication_id', 'publication', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_notification_user', '{{%notification}}');
        $this->dropForeignKey('fk_notification_publication', '{{%notification}}');
        $this->dropTable('{{%notification}}');
    }
}
