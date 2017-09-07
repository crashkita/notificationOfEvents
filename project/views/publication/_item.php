<?php
use yii\helpers\Html;
/**
 * @var \app\models\Publication $model
 */
?>
<div class="row header">
    <?php
    if (Yii::$app->user->can('updateOwnPublication', ['publication' => $model])) {
        echo Html::a('edit', ['publication/edit', 'id' => $model->id], [ 'data-toggle' => 'link-modal']);
    }
    ?>
    <?=Html::a($model->name, ['publication/view', 'id' => $model->id], ['class' => 'header']);?>
</div>
<div class="row image">
    <?=Html::img($model->imageUrl, ['width' => 300, 'height' => 200])?>
</div>
<div class="row annotation">
    <?=Html::encode($model->annotation)?>
</div>
