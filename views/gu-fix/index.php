<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GuFixSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gu Fixes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-fix-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Gu Fix', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'type',
            'name',
            'pingyin',
            'total',
            // 'circulation',
            // 'hand_rate',
            // 'hand_num',
            // 'left_num',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
