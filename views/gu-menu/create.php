<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GuMenu */

$this->title = '添加菜单';
$this->params['breadcrumbs'][] = ['label' => '菜单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gu-menu-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
