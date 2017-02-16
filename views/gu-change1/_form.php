<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GuChange1 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gu-change1-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'yesterday')->textInput() ?>

    <?= $form->field($model, 'today')->textInput() ?>

    <?= $form->field($model, 'max')->textInput() ?>

    <?= $form->field($model, 'min')->textInput() ?>

    <?= $form->field($model, 'deal_count')->textInput() ?>

    <?= $form->field($model, 'deal_num')->textInput() ?>

    <?= $form->field($model, 'change_rate')->textInput() ?>

    <?= $form->field($model, 'amplitude')->textInput() ?>

    <?= $form->field($model, 'current_date')->textInput() ?>

    <?= $form->field($model, 'current_date_')->textInput() ?>

    <?= $form->field($model, 'z_j_c')->textInput() ?>

    <?= $form->field($model, 'current')->textInput() ?>

    <?= $form->field($model, 'rate')->textInput() ?>

    <?= $form->field($model, 'up_limit')->textInput() ?>

    <?= $form->field($model, 'down_limit')->textInput() ?>

    <?= $form->field($model, 'sh_rate')->textInput() ?>

    <?= $form->field($model, 'sh_num')->textInput() ?>

    <?= $form->field($model, 'sz_rate')->textInput() ?>

    <?= $form->field($model, 'sz_num')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
