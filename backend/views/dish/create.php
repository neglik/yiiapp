<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Dish */

$this->title = Yii::t('app', 'Create Dish');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dishes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="dish-create">

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
