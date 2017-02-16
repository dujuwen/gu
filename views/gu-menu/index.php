<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GuMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '菜单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-menu-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加菜单', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'label',
            'url:url',
            [
                'label' => '状态',
                'value' => function($model) {
                    return User::$status[$model->status];
                }
            ],
            'orde',

            [
                'class'    => 'yii\grid\ActionColumn',
                'header'=>'操作',
                'template' => '{view} {update} {delete}',
            ]
        ],
    ]); ?>
</div>
