<?php

namespace app\commands;

use app\rbac\AuthorRule;
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

        // add "createPublication" permission
        $createPublication = $auth->createPermission('createPublication');
        $createPublication->description = 'Create a publication';
        $auth->add($createPublication);

        // add "updatePublication" permission
        $updatePublication = $auth->createPermission('updatePublication');
        $updatePublication->description = 'Update publication';
        $auth->add($updatePublication);

        $rule = new AuthorRule();
        $auth->add($rule);

        // add the "updateOwnPublication" permission and associate the rule with it.
        $updateOwnPublication = $auth->createPermission('updateOwnPublication');
        $updateOwnPublication->description = 'Update own publication';
        $updateOwnPublication->ruleName = $rule->name;
        $auth->add($updateOwnPublication);

        // "updateOwnPublication" will be used from "updatePublication"
        $auth->addChild($updateOwnPublication, $updatePublication);

        // allow "author" to update their own publications
        $auth->addChild($moderator, $updateOwnPublication);

        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $admin->ruleName = $groupRule->name;
        $auth->add($admin);
        $auth->addChild($admin, $moderator);
        $auth->addChild($admin, $updatePublication);
    }
}
