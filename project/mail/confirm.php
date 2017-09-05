<?php
/**
 * @var \app\models\User $model
 * @var string $url
 */
?>
<p>
    Уважаемый(-ая) <?=$model->name?>
</p>
<p>
    Поздравляем Вас с успешной регистрацией на сайте DRIVENN.RU.</p>
<p>
    Для завершения регистрации перейдите по ссылке: <br>
    <?=\yii\helpers\Html::a('активировать аккаунт', $url)?>
</p>
