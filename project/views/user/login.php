<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 *
 * @var \app\models\LoginForm $model
 */
$this->params['h1'] = 'Вход';
?>

<div class="row">
    <?php
    $form = ActiveForm::begin([
        'options' => [
            'class' => 'p-b-1 col-lg-4 central-block',
            'autocomplete' => 'off',
            'id' => 'login-form'
        ],
        'method' => 'post',
        'enableClientScript' => false,
    ]);
    ?>
        <div class="row">
            <div class="col-lg-12">
                <?= $form->field($model, 'username')->textInput()?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?= $form->field($model, 'password')->passwordInput()?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?= $form->field($model, 'rememberMe')->checkbox()?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <?=Html::a('Забыл пароль?', ['user/request-password-reset'])?>
            </div>
            <div class="col-lg-6">
                <?=Html::a('Регистрация', ['user/registration'])?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-single']) ?>
            </div>
        </div>
    <?php ActiveForm::end()?>
</div>

