<?php

namespace app\rbac;

use Yii;
use yii\rbac\Rule;
use app\models\User;

/**
 * User group rule class.
 */
class UserGroupRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'userGroup';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;

            if ($item->name === 'admin') {
                return $role === User::ROLE_ADMIN;
            } elseif ($item->name === 'moderator') {
                return $role === User::ROLE_MODERATOR || $role === User::ROLE_ADMIN;
            }  elseif ($item->name === 'user') {
                return $role === User::ROLE_USER  || $role === User::ROLE_PRODUCT_MODERATOR || $role === User::ROLE_MODERATOR || $role === User::ROLE_ADMIN;
            }
        }
        return false;
    }
}
