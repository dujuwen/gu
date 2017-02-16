<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\GuMonitor;

/* @var $this yii\web\View */
/* @var $model app\models\GuChange1Search */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gu-change1-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

	<div class="row">
        <div class="col-md-6"> <?= $form->field($model, 'code')->dropDownList(GuMonitor::getCodeName(), ['prompt' => '选择']) ?> </div>
    </div>

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
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
