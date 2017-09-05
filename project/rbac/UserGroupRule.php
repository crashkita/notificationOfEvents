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
    public $name = 'isAuthor';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['publication']) ? $params['publication']->created_by == $user : false;
    }
}
