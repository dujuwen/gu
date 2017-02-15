<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GuFix */

$this->title = 'Update Gu Fix: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Gu Fixes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gu-fix-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
