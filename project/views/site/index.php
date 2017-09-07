<?php
use yii\helpers\Html;
use app\widgets\ListView;
/* @var $this yii\web\View */

$this->title = 'Notification of event';
echo Html::tag('h3', 'Публикации');
echo ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{pager}\n{sizer}",
    'itemView' => '//publication/_item',
    'options' => ['class' => 'row '],
    'itemOptions' => ['class' => 'col-lg-4 publication'],
    'emptyText' => 'К сожалению, публикаций нет.'
]);
?>