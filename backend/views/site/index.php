<?php

/* @var $this yii\web\View */

use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', Yii::$app->name);

?>

<div class="container">

        <div class="main-page-item">
            <h2 class="text-muted text-center">
                <?= Yii::t('app', Yii::$app->name) ?>
            </h2>
        </div>

        <div class="row main-page-item">
            <div class="col-lg-4 col-lg-offset-4">
                <a href="<?= Url::toRoute(['ingredient/index']) ?>" class="btn btn-default btn-lg btn-block">
                    <h4 class="text-muted">
                        <?= Yii::t('app', 'Ingredients') ?>
                    </h4>
                </a>
            </div>
        </div>

        <div class="row main-page-item">
            <div class="col-lg-4 col-lg-offset-4">
                <a href="<?= Url::toRoute(['dish/index']) ?>" class="btn btn-default btn-lg btn-block">
                    <h4 class="text-muted">
                        <?= Yii::t('app', 'Dishes') ?>
                    </h4>
                </a>
            </div>
        </div>

        <div class="row main-page-item">
            <div class="col-lg-4 col-lg-offset-4">
                <a href="<?= Url::toRoute(['admin/index']) ?>" class="btn btn-default btn-lg btn-block">
                    <h4 class="text-muted">
                        <?= Yii::t('app', 'Administrators') ?>
                    </h4>
                </a>
            </div>
        </div>

</div>
