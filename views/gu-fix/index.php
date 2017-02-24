<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\GuFix;
use app\library\Util;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GuFixSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '股票';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-fix-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a('Create Gu Fix', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            'code',
            [
                'label' => '证券交易所',
                'value' => function($model){
                    return GuFix::$types2[$model->type];
                },
            ],
            //'pingyin',
            [
                'attribute' => 'total',
                'value' => function($model){
                    return Util::formatMoney($model->total);
                },
            ],
            [
                'attribute' => 'circulation',
                'value' => function($model){
                    return Util::formatMoney($model->circulation);
                },
            ],
            [
                'attribute' => 'hand_rate',
                'value' => function($model){
                    return ($model->hand_rate * 100) . '%';
                },
            ],
            [
                'attribute' => 'hand_num',
                'value' => function($model){
                    return Util::formatMoney($model->hand_num);
                },
            ],
            [
                'attribute' => 'left_num',
                'value' => function($model){
                    return Util::formatMoney($model->left_num);
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
            ],
        ],
    ]); ?>
</div>
;