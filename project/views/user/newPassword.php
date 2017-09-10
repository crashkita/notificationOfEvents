<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->params['h1'] = $this->title = 'Установить пароля';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="site-password">
    <div class="row">
        <div class="col-lg-6 central-block">
            <?php $form = ActiveForm::begin([
                'id' => 'password-form',
                'options' => [
                    'class' => 'p-b-1 col-lg-12',
                    'autocomplete' => 'off',
                    'id' => 'reset-password-form'
                ],
                'method' => 'post',
                'enableClientScript' => false
            ]); ?>

            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

            <hr>
            <div class="col-lg-12">
                <?= Html::submitButton('Сохранить пароль', ['class' => 'btn btn-primary btn-single']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
