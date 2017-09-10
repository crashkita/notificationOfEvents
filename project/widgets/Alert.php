<?php
namespace app\widgets;

use app\models\Notification;
use Yii;
use yii\helpers\Html;
use yii\bootstrap\Alert as BootstrapAlert;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Alert extends \yii\bootstrap\Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - key: the name of the session flash variable
     * - value: the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];
    /**
     * @var array the options for rendering the close button tag.
     * Array will be passed to [[\yii\bootstrap\Alert::closeButton]].
     */
    public $closeButton = [];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->publicationRender() . $this->flashRender();
    }

    /**
     * Render alert from session
     * @return string
     */
    protected function flashRender()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        $content = '';

        foreach ($flashes as $type => $flash) {
            if (!isset($this->alertTypes[$type])) {
                continue;
            }

            foreach ((array) $flash as $i => $message) {
                $content .= BootstrapAlert::widget([
                    'body' => $message,
                    'closeButton' => $this->closeButton,
                    'options' => array_merge($this->options, [
                        'id' => $this->getId() . '-' . $type . '-' . $i,
                        'class' => $this->alertTypes[$type] . $appendClass,
                    ]),
                ]);
            }

            $session->removeFlash($type);
        }

        return $content;
    }

    /**
     * Render publication alert
     * @return string
     */
    protected function publicationRender()
    {
        $content = '';
        if (!Yii::$app->user->isGuest) {
            $userId = Yii::$app->user->id;
            $notifications = Notification::find()->andWhere(
                [
                    'status_id' => Notification::STATUS_ACTIVE,
                    'user_id' => $userId
                ]
            )->andWhere([
                'IN', 'type_id',
                [
                    Notification::TYPE_BROWSER_AND_EMAIL,
                    Notification::TYPE_BROWSER
                ]
            ])->all();

            $appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

            foreach ($notifications as $index => $notification) {
                $publication = $notification->publication;
                $message = 'Новая побликация ' . Html::a($publication->name, ['publication/view', 'id' => $publication->id]);

                $closeButtonOptions = [
                    'data' => [
                        'toggle' => "alert-hide",
                        'notification-id' => $notification->id
                    ]

                ];
                $content .= BootstrapAlert::widget([
                    'body' => $message,
                    'closeButton' => $closeButtonOptions,
                    'options' => array_merge($this->options, [
                        'id' => $this->getId() . '-info-publication-' . $index,
                        'class' => $this->alertTypes['info'] . $appendClass,
                    ]),
                ]);
            }
        }

        return $content;
    }
}
