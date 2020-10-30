<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Ingredient */

$this->title = Yii::t(
    'app',
    'Update Ingredient: {name}',
    ['name' => $model->name]
);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ingredients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>
<div class="ingredient-update">

    <h3 class="text-muted">
        <?= Html::encode($this->title) ?>
    </h3>

    <?=
        $this->render(
            '_form',
            [
                'model' => $model,
            ]
        )
    ?>

</div>
