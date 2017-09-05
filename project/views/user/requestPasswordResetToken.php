<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
/**
 *
 * @var \app\models\PasswordResetRequestForm $model
 */
$this->params['h1'] = 'Восстановление пароля';
?>
<div class="row">
    <div class="col-lg-12">
        <p class="text-center">
            Для восстановления пароля, укажите свой email или логин.
        </p>
    </div>
    <div class="col-lg-12">

        <div class="row">
            <?php
            $form = ActiveForm::begin([
                'options' => [
                    'class' => 'p-b-1 ajax-form central-block col-lg-8',
                    'autocomplete' => 'off',
                    'id' => 'reset-password-form'
                ],
                'method' => 'post',
                'enableClientScript' => false,
            ]);
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <?=$form->field($model, 'email')->textInput()?>
                </div>
                <hr>
                <div class="col-lg-12">
                    <?= Html::submitButton('Восстановить пароль', ['class' => 'btn btn-primary btn-single']) ?>
                </div>
            </div>
            <?php ActiveForm::end();?>
        </div>


    </div>
</div>
