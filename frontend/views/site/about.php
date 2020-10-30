<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('app', 'About');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-about">
    <h2 class="text-muted">
        <?= Html::encode($this->title) ?>
    </h2>

    <p>
        <?= Yii::t('app', 'Yiiapp Application &copy; 2020') ?>
    </p>
</div>
