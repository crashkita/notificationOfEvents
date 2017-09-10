<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%notification}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $publication_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $status_id
 * @property int $type_id
 *
 * @property Publication $publication
 * @property User $user
 *
 * @package app\models
 */
class Notification extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_HIDDEN = 2;

    const TYPE_EMAIL = 1;
    const TYPE_BROWSER = 2;
    const TYPE_BROWSER_AND_EMAIL = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status_id', 'default', 'value' => self::STATUS_ACTIVE],
            ['status_id', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_HIDDEN]],
            ['type_id', 'default', 'value' => self::TYPE_BROWSER],
            ['type_id', 'in', 'range' => [self::TYPE_BROWSER, self::TYPE_EMAIL, self::TYPE_BROWSER_AND_EMAIL]],
            [['user_id', 'publication_id', 'created_at', 'updated_at', 'status_id'], 'integer'],
            [['publication_id'], 'exist', 'skipOnError' => true, 'targetClass' => Publication::className(), 'targetAttribute' => ['publication_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'publication_id' => 'Publication ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status_id' => 'Status ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPublication()
    {
        return $this->hasOne(Publication::className(), ['id' => 'publication_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function sendEmail()
    {
        $url = Url::to(['publication/view', 'id' => $this->publication->id], true);
        return Yii::$app
            ->mailer
            ->compose(
                'notification',
                [
                    'user' => $this->user,
                    'url' => $url,
                    'publicationName' => $this->publication->name
                ]
            )
            ->setFrom(Yii::$app->params['senderEmail'])
            ->setTo($this->user->email)
            ->setSubject('Новая публикация')
            ->send();
    }
}
