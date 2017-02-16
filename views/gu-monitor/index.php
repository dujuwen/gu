<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\GuMonitor;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GuMonitorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '需要记录活动数据股票';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-monitor-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'code',
            [
                'label' => '状态',
                'value' => function($model) {
                    return GuMonitor::$status[$model->status];
                },
            ],

            [   
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
            ],
        ],
    ]); ?>
</div>
