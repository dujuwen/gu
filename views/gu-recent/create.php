<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GuRecent */

$this->title = '添加最近增减仓';
$this->params['breadcrumbs'][] = ['label' => '最近增减仓', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-recent-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
