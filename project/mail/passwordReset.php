<?php
use yii\helpers\Html;
/**
 * @var \app\models\User $user
 * @var string $newPass
 */
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->password_reset_token]);
?>
<p>
    Вы получили это сообщение, так как являетесь зарегистрированным пользователем сайта http://www.drivenn.ru
</p>
<p>
    Имя (ФИО): <?=$user->name?>
</p>
<p>
    Логин: <?=$user->username?>
</p>
<p>
    Для востановления пароля перейдите по ссылке: <?=Html::a('востановить пароль', $resetLink)?>
</p>