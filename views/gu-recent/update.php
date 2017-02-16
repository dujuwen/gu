<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GuRecent */

$this->title = 'Update Gu Recent: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gu Recents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gu-recent-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
