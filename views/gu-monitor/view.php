<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\GuMonitor */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '添加监视数据', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-monitor-view">

    <p>
        <?= Html::a('继续添加', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'code',
            'status',
            'created_at',
        ],
    ]) ?>

</div>
