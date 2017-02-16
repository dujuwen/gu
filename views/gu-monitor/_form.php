<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\GuMonitor;

/* @var $this yii\web\View */
/* @var $model app\models\GuMonitor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gu-monitor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput()->dropDownList(GuMonitor::$status) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
