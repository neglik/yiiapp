<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Admin;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */
/* @var $usernameReadonly boolean */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
        $form->field($model, 'username')->textInput(
            [
                'maxlength' => true,
                'readonly' => $usernameReadonly,
                'autofocus' => !$usernameReadonly,
            ]
        )
    ?>

    <?=
        $form->field($model, 'email')->textInput(
            [
                'maxlength' => true,
                'autofocus' => $usernameReadonly,
            ]
        )
    ?>

    <?=
        $form->field($model, 'status')->dropDownList(
            [
                Admin::STATUS_ACTIVE => Yii::t('app', 'Active'),
                Admin::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
                Admin::STATUS_DELETED => Yii::t('app', 'Deleted'),
            ]
        );
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
