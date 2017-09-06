<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success', 'data-toggle' => 'link-modal']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'username',
            'email:email',
            'name',
            [
                'attribute' => 'created_at',
                'format' => ['date'],
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'clearBtn' => true
                    ]
                ]),
                'headerOptions' => ['width' => 110]
            ],
            [
                'attribute' => 'last_login',
                'format' => ['date'],
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'last_login',
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'clearBtn' => true
                    ]
                ]),
                'headerOptions' => ['width' => 110]
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
