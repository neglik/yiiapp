<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Ingredient */

$this->title = Yii::t('app', 'Create Ingredient');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ingredients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="ingredient-create">

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
