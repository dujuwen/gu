<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\GuMonitor;

/* @var $this yii\web\View */
/* @var $model app\models\GuRecentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gu-recent-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

	<div class="row">
        <div class="col-md-6"> <?= $form->field($model, 'day') ?> </div>
        <div class="col-md-6"> <?= $form->field($model, 'code')->dropDownList(GuMonitor::getCodeName(), ['prompt' => '选择']) ?> </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
