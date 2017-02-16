<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\GuFix;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GuChange1Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '每日变化';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-change1-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('添加', ['create'], ['class' => 'btn btn-success']) ?>
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
//             'yesterday',
//             'today',
//             'max',
            // 'min',
            // 'deal_count',
            // 'deal_num',
            // 'change_rate',
            // 'amplitude',
            // 'current_date_',
            'z_j_c',
            [
                'label' => '当前时间',
                'attribute' => 'current_date',
                'format' => ['date', 'php:Y-m-d H:i:s']
            ],
            // 'current',
            // 'rate',
            // 'up_limit',
            // 'down_limit',
            // 'sh_rate',
            // 'sh_num',
            // 'sz_rate',
            // 'sz_num',
            // 'created_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
            ],
        ],
    ]); ?>
</div>
