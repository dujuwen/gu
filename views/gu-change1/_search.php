<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GuChange1Search */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gu-change1-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'yesterday') ?>

    <?= $form->field($model, 'today') ?>

    <?= $form->field($model, 'max') ?>

    <?php // echo $form->field($model, 'min') ?>

    <?php // echo $form->field($model, 'deal_count') ?>

    <?php // echo $form->field($model, 'deal_num') ?>

    <?php // echo $form->field($model, 'change_rate') ?>

    <?php // echo $form->field($model, 'amplitude') ?>

    <?php // echo $form->field($model, 'current_date') ?>

    <?php // echo $form->field($model, 'current_date_') ?>

    <?php // echo $form->field($model, 'z_j_c') ?>

    <?php // echo $form->field($model, 'current') ?>

    <?php // echo $form->field($model, 'rate') ?>

    <?php // echo $form->field($model, 'up_limit') ?>

    <?php // echo $form->field($model, 'down_limit') ?>

    <?php // echo $form->field($model, 'sh_rate') ?>

    <?php // echo $form->field($model, 'sh_num') ?>

    <?php // echo $form->field($model, 'sz_rate') ?>

    <?php // echo $form->field($model, 'sz_num') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
