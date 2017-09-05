<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%publication}}".
 *
 * @property int $id
 * @property string $image
 * @property string $annotation
 * @property string $text
 * @property int $created_at
 * @property int $updated_at
 * @property int $status_id
 * @property string $name
 */
class Publication extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_HIDDEN = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%publication}}';
    }

    public static function status()
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_HIDDEN => 'Неактивен'
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at', 'status_id'], 'integer'],
            ['status_id', 'in', 'range' => array_keys(self::status())],
            [['image', 'name'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'safe'],
            [['annotation'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Картинка',
            'annotation' => 'Аннотация',
            'text' => 'Контент',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'status_id' => 'Статус',
            'name' => 'Название',
        ];
    }
}
