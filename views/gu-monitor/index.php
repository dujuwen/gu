<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\GuMonitor;
use app\models\GuFix;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GuMonitorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '监测目标';
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
            [
                'label' => '股票代码',
                'value' => function($model) {
                    return GuFix::getNameByCode($model->code);
                },
            ],
            [
                'label' => '状态',
                'value' => function($model) {
                    return GuMonitor::$status[$model->status];
                },
            ],
            'orde',

            [   
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
            ],
        ],
    ]); ?>
</div>
