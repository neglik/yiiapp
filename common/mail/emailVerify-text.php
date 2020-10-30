<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<?= Yii::t('app', 'Hello') ?> <?= $user->username ?>,

<?= Yii::t('app', 'Follow the link below to verify your email:') ?>

<?= $verifyLink ?>
