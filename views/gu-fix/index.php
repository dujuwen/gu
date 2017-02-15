<?php

use yii\helpers\Html;
use yii\grid\GridView;

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
            'type',
            'name',
            'pingyin',
            'total',
            'circulation',
            'hand_rate',
            'hand_num',
            'left_num',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
