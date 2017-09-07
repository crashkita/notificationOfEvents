<?php

namespace app\models;

use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use Yii;

/**
 * Class User
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $name
 * @property integer $last_login
 * @property integer $notification_type_id
 * @property string $confirmation_token
 * @property string $password write-only password
 * @package app\models
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    const NOTIFICATION_EMAIL = Notification::TYPE_EMAIL;
    const NOTIFICATION_BROWSER = Notification::TYPE_BROWSER;
    const NOTIFICATION_ALL = Notification::TYPE_BROWSER_AND_EMAIL;

    const ROLE_USER = 10;
    const ROLE_MODERATOR = 20;
    const ROLE_ADMIN = 30;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public static function notification()
    {
        return [
            self::NOTIFICATION_EMAIL => 'На email',
            self::NOTIFICATION_BROWSER => 'В браузере',
            self::NOTIFICATION_ALL => 'В браузере и на email'
        ];
    }

    public static function role()
    {
        return [
            self::ROLE_USER => 'Пользователь',
            self::ROLE_MODERATOR => 'Модератор',
            self::ROLE_ADMIN => 'Администратор'
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['username'], 'string', 'max' => 255],
            [['password_hash'], 'string'],
            ['email', 'email'],
            [['email', 'username'], 'unique'],
            ['role_id', 'in', 'range' => array_keys(self::role())],
            ['notification_type_id', 'in', 'range' => array_keys(self::notification())],
            [['username'], 'safe', 'on' => ['update', 'create']],
            ['role_id', 'default', 'value' => self::ROLE_USER],
            [['last_login', 'password_hash', 'password', 'confirmation_token'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'created_at' => 'Дата регистрации',
            'updated_at' => 'Дата обновления',
            'name' => 'Имя',
            'email' => 'Email',
            'last_login' => 'Заход',
            'notification_type_id' => 'Тип оповещения',
            'role_id' => 'Роль'
        ];
    }

    /**
     * Creates new user account. It generates password if it is not provided by user.
     *
     * @return bool
     */
    public function create()
    {
        if (!$this->isNewRecord) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $this->password = $this->password == null ?? Yii::$app->security->generateRandomString();

        if (!$this->save()) {
            return false;
        }

        return true;
    }


    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()->where(['or', ['username' => $username], ['email' => $username]])->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        if (empty($password)) {
            return;
        }
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getPassword()
    {
        return null;
    }

    /**
     * After login behavior
     */
    public function afterLogin()
    {
        $this->last_login = time();
        $this->updateAttributes(['last_login', 'password_hash']);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return bool
     */
    public function confirm()
    {
        $this->confirmation_token = null;
        return $this->save();
    }

    public function generateConfirm()
    {
        $this->confirmation_token = Yii::$app->security->generateRandomString();
    }

    /**
     * @return bool
     */
    public function sendConfirm()
    {
        $url = Url::to(['user/confirm', 'token' => $this->confirmation_token], true);
        return Yii::$app
            ->mailer
            ->compose(
                'confirm',
                [
                    'user' => $this,
                    'url' => $url
                ]
            )
            ->setFrom(Yii::$app->params['senderEmail'])
            ->setTo($this->email)
            ->setSubject('Активация аккаунта пользователя')
            ->send();
    }
}
