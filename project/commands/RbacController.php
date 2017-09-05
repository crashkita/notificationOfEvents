<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use app\rbac\UserGroupRule;

/**
 * RBAC console controller.
 */
class RbacController extends Controller
{
    /**
     * Initial RBAC action
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Rules
        $groupRule = new UserGroupRule();
        $auth->add($groupRule);

        // Roles
        $user = $auth->createRole('user');
        $user->description = 'User';
        $user->ruleName = $groupRule->name;
        $auth->add($user);

        $moderator = $auth->createRole('moderator');
        $moderator->description = 'Moderator';
        $moderator->ruleName = $groupRule->name;
        $auth->add($moderator);
        $auth->addChild($moderator, $user);

        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $admin->ruleName = $groupRule->name;
        $auth->add($admin);
        $auth->addChild($admin, $moderator);
    }
}
