<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GuFixSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gu-fix-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'pingyin') ?>

    <?= $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'circulation') ?>

    <?php // echo $form->field($model, 'hand_rate') ?>

    <?php // echo $form->field($model, 'hand_num') ?>

    <?php // echo $form->field($model, 'left_num') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
