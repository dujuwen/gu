<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GuChange1 */

$this->title = 'Update Gu Change1: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gu Change1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gu-change1-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
