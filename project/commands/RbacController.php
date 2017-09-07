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
        $createpublication = $auth->createPermission('createPublication');
        $createpublication->description = 'Create a publication';
        $auth->add($createpublication);

        // add "updatePublication" permission
        $updatepublication = $auth->createPermission('updatePublication');
        $updatepublication->description = 'Update publication';
        $auth->add($updatepublication);

        $rule = new AuthorRule();
        $auth->add($rule);

        // add the "updateOwnPublication" permission and associate the rule with it.
        $updateOwnPublication = $auth->createPermission('updateOwnPublication');
        $updateOwnPublication->description = 'Update own publication';
        $updateOwnPublication->ruleName = $rule->name;
        $auth->add($updateOwnPublication);

        // "updateOwnPublication" will be used from "updatepublication"
        $auth->addChild($updateOwnPublication, $updatepublication);

        // allow "author" to update their own publications
        $auth->addChild($moderator, $updateOwnPublication);

        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $admin->ruleName = $groupRule->name;
        $auth->add($admin);
        $auth->addChild($admin, $moderator);
        $auth->addChild($admin, $updatepublication);
    }
}
