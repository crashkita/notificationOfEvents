<?php
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 */

$this->params['h1'] = 'Подтверждение регистрации';
?>
<p>Активация аккаунта завершилась</p>
<p>Вы успешно активировали свой аккаунт. Теперь Вы можете можете
    войти <?php echo Html::a('в личный кабинет', 'user/login'); ?>, используя указанный Вами логин и
    пароль.</p>