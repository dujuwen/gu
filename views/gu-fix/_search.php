<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\GuFix;

/* @var $this yii\web\View */
/* @var $model app\models\GuFixSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gu-fix-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

	<div class="row">
        <div class="col-md-4"> <?= $form->field($model, 'id') ?> </div>
        <div class="col-md-4"> <?= $form->field($model, 'type')->dropDownList(GuFix::$types2, ['prompt' => '选择']) ?> </div>
        <div class="col-md-4"> <?= $form->field($model, 'name') ?> </div>
    </div>

	<div class="row">
        <div class="col-md-4"> <?= $form->field($model, 'pingyin') ?> </div>
        <div class="col-md-4"> <?= $form->field($model, 'total') ?> </div>
        <div class="col-md-4"> <?= $form->field($model, 'circulation') ?> </div>
    </div>

	<div class="row">
        <div class="col-md-4"> <?= $form->field($model, 'hand_rate') ?> </div>
        <div class="col-md-4"> <?= $form->field($model, 'hand_num') ?> </div>
        <div class="col-md-4"> <?= $form->field($model, 'left_num') ?> </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
