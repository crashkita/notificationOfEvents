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
    <?php Pjax::begin(); ?>
    <p>
        <?= Html::a('Create Publication', ['create'], ['class' => 'btn btn-success', 'data-toggle' => 'link-modal']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'annotation',
            'text:ntext',
            [
                'attribute' => 'status_id',
                'format' => 'raw',
                'value' => function (Publication $model) {
                    return Html::dropDownList('id' . $model->id, $model->status_id, $model::status(), ['class' => 'select form-control', 'data-id' => $model->id, 'prompt' => '---']);
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
                'template' => '{update}',
                'buttonOptions' => ['data-toggle' => 'link-modal']
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
