<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GuMonitor */

$this->title = '添加';
$this->params['breadcrumbs'][] = ['label' => '需要监视的数据', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-monitor-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
