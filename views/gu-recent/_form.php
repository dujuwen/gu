<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GuRecent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gu-recent-form">

    <?php $form = ActiveForm::begin(['fieldClass' => 'app\library\DateField']); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'day')->textInput() ?>
    <?php // echo $form->field($model, 'day')->dateTimePicker(['plugin_options' => ['startDate' => date('Y-m-d H:i:s'), 'minuteStep' => 10]]) ?>

    <?= $form->field($model, 'final_zjc')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
