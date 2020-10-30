<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */

$this->title = Yii::t('app', 'Create Administrator');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Administrators'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="admin-create">

    <h3 class="text-muted">
        <?= Html::encode($this->title) ?>
    </h3>

    <?=
        $this->render(
            '_form',
            [
                'model' => $model,
                'usernameReadonly' => false,
            ]
        )
    ?>

</div>
