<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Publication */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Publications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="publication-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <?=Html::img($model->imageUrl, ['width' => 900, 'height' => 400])?>
    </div>
    <p>
        <?=$model->text?>
    </p>
</div>
