<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GuChange1 */

$this->title = 'Create Gu Change1';
$this->params['breadcrumbs'][] = ['label' => 'Gu Change1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-change1-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
