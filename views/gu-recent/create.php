<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GuRecent */

$this->title = 'Create Gu Recent';
$this->params['breadcrumbs'][] = ['label' => 'Gu Recents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-recent-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
