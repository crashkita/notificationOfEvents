<?php

namespace app\controllers;

use app\models\Notification;
use yii\web\Controller;
use Yii;

/**
 * Class NotificationController
 * @package app\controllers
 */
class NotificationController extends Controller
{
    public function actionHide()
    {
        $id = Yii::$app->request->get('id');
        Notification::updateAll(
            [
                'status_id' => Notification::STATUS_HIDDEN
            ],
            [
                'id' => $id
            ]
        );
    }
}