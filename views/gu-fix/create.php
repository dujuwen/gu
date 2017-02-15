<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GuFix */

$this->title = 'Create Gu Fix';
$this->params['breadcrumbs'][] = ['label' => 'Gu Fixes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-fix-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
