<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\GuFix */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '基本信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-fix-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定删除?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type',
            'name',
            'pingyin',
            'total',
            'circulation',
            'hand_rate',
            'hand_num',
            'left_num',
        ],
    ]) ?>

</div>
