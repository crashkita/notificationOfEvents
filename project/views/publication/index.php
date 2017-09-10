<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;
use yii\helpers\Url;
use app\models\Publication;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PublicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Publications';
$this->params['breadcrumbs'][] = $this->title;
$editorUrl = Url::to(['editable']);

$this->registerJs("
    $('.select').on('change', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        var value = $(this).val();
        var data = {id:id, status:value};
        $.ajax({
            type: 'GET',
            url: '{$editorUrl}',
            data: data,
            success: function (response) {
                if (response.success) {
                    alert('Данные обновлены');
                    location.reload();
                } else {
                    console.log(response);
                }
            }
        });
        return false;
    });
");
?>
<div class="publication-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    if (Yii::$app->user->can('moderate')) {
        echo Html::a('Create Publication', ['create'], ['class' => 'btn btn-success', 'data-toggle' => 'link-modal']);
    }
    ?>
    <?php Pjax::begin(); ?>
    <p>

    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            [
                'attribute' => 'status_id',
                'format' => 'raw',
                'value' => function (Publication $model) {
                    if (Yii::$app->user->can('updatePublication', ['publication' => $model])) {
                        return Html::dropDownList('id' . $model->id, $model->status_id, $model::status(), ['class' => 'select form-control', 'data-id' => $model->id, 'prompt' => '---']);
                    }
                    return $model::status()[$model->status_id];
                },
                'filter' => Publication::status()
            ],
            [
                'attribute' => 'created_at',
                'format' => 'date',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'locale' => [
                            'format' => 'd.m.Y'
                        ],
                    ],
                ]),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        if (!Yii::$app->user->can('updatePublication', ['publication' => $model])) {
                            return null;
                        }
                        $title = Yii::t('yii', 'Update');
                        $options = [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '0',
                            'data-toggle' => 'link-modal'
                        ];
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
                        return Html::a($icon, $url, $options);
                    },
                    'delete' => function ($url, $model, $key) {
                        if (!Yii::$app->user->can('updatePublication', ['publication' => $model])) {
                            return null;
                        }
                        $title = Yii::t('yii', 'Delete');
                        $options = [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '0',
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                        ];
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);
                        return Html::a($icon, $url, $options);
                    }
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
