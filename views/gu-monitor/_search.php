<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\GuMonitor;

/* @var $this yii\web\View */
/* @var $model app\models\GuMonitorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gu-monitor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

	<div class="row">
        <div class="col-md-4"> <?= $form->field($model, 'id') ?> </div>
        <div class="col-md-4"> <?= $form->field($model, 'code') ?> </div>
        <div class="col-md-4"> <?= $form->field($model, 'status')->dropDownList(GuMonitor::$status, ['prompt' => '选择']) ?> </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
