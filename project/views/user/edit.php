<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 *
 * @var \app\models\User $model
 */
$this->params['h1'] = 'Мой профиль';
?>

<div class="row">
    <?php
    $form = ActiveForm::begin([
        'options' => [
            'class' => 'p-b-1 col-lg-8 central-block',
            'autocomplete' => 'off',
            'id' => 'user-edit-form'
        ],
        'method' => 'post',
        'enableClientScript' => false,
    ]);
    ?>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'username')->textInput()?>
        </div>
        <div class="col-lg-6">
            <?=Html::a('изменить пароль', ['user/request-password-reset'])?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'email')->textInput()?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'name')->textInput()?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($model, 'notification_type_id')->dropDownList($model::notification())?>
        </div>
        <div class="col-lg-12">
            <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary btn-single']) ?>
        </div>
    </div>
    <?php ActiveForm::end();?>
</div>