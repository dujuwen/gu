<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GuRecentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '最近几日增减仓情况';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-recent-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加近期', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            'code',
            'day',
            'final_zjc',
            'created_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
            ],
        ],
    ]); ?>
</div>
