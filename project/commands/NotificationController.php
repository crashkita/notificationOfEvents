<?php

namespace app\commands;

use app\models\Notification;
use yii\console\Controller;

/**
 * Class NotificationController
 * @package app\commands
 */
class NotificationController extends Controller
{
    public function actionSend()
    {
        $notifications = Notification::find()
            ->andWhere(['status_id' => Notification::STATUS_ACTIVE])
            ->andWhere([
                'type_id' => [
                    Notification::TYPE_BROWSER_AND_EMAIL,
                    Notification::TYPE_EMAIL
                ]
            ])->all();

        foreach ($notifications as $notification) {
            /* @var Notification $notification*/
            $notification->sendEmail();
        }
    }
}