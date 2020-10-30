<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Login');

?>

<script>
function handleChangePasswordClick()
{
    if ($('#change-password-switch').is(':checked')) {
        $('#change-password-block').show();
    } else {
        $('#change-password-block').hide();
    }
}
</script>

<div class="site-login">

    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, Yii::t('app', 'username'))->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, Yii::t('app', 'password'))->passwordInput() ?>

        <div id="change-password-block"<?= $model->changePassword ? '' : ' style="display: none"' ?>>
            <?= $form->field($model, Yii::t('app', 'newPassword'))->passwordInput() ?>
            <?= $form->field($model, Yii::t('app', 'confirmNewPassword'))->passwordInput() ?>
        </div>

        <?= $form->field($model, Yii::t('app', 'rememberMe'))->checkbox() ?>

        <?= $form->field($model, Yii::t('app', 'changePassword'))->checkbox(['id' => 'change-password-switch', 'onclick' => 'handleChangePasswordClick();']) ?>

        <div class="form-group">
            <?=
                Html::submitButton(
                    Yii::t('app', 'Login'),
                    ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']
                )
            ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>