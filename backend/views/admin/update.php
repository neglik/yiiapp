<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */

$this->title = Yii::t(
    'app',
    'Update Administrator: {username}',
    ['username' => $model->username]
);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Administrators'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="admin-update">

    <h3 class="text-muted">
        <?= Html::encode($this->title) ?>
    </h3>

    <?=
        $this->render(
            '_form',
            [
                'model' => $model,
                'usernameReadonly' => true,
            ]
        )
    ?>

</div>
