<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GuChange1Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '每日变化';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-change1-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'code',
//             'yesterday',
//             'today',
//             'max',
            // 'min',
            // 'deal_count',
            // 'deal_num',
            // 'change_rate',
            // 'amplitude',
            // 'current_date',
            // 'current_date_',
            'z_j_c',
            // 'current',
            // 'rate',
            // 'up_limit',
            // 'down_limit',
            // 'sh_rate',
            // 'sh_num',
            // 'sz_rate',
            // 'sz_num',
            // 'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>