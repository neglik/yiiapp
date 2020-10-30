<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model common\models\Dish */
/* @var $dishIngredientSearchModel backend\models\DishIngredientSearch */
/* @var $dishIngredientDataProvider yii\data\ActiveDataProvider */
/* @var $ingredientSearchModel common\models\IngredientSearch */
/* @var $ingredientDataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('app', 'Dish Ingredients: {name}', ['name' => $model->name]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dishes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="dish-index">

    <h3 class="text-muted">
        <?= Html::encode($this->title) ?>
    </h3>

    <?php Pjax::begin(); ?>

    <?=
        GridView::widget(
            [
                'dataProvider' => $dishIngredientDataProvider,
                'layout' => "{items}",
                'columns' => [
                    'name',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '<div class="text-center">{delete-ingredient}</div',
                        'buttons' => [
                            'delete-ingredient' => function ($url, $ingediemtModel, $key) use ($model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-remove"></span>',
                                    $url . '&dishID=' . $model->id,
                                    [
                                        'title' => Yii::t('app', 'Remove Ingredient'),
                                        //'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                        'data-method' => 'post',
                                        'data-pjax' => '0',
                                    ]
                                );
                            },
                        ],
                        'options' => [
                            'width' => 50,
                        ],
                    ],
                ],
            ]
        );
    ?>

    <h3 class="text-muted">
        <?= Yii::t('app', 'Ingredients Available To Add')?>
    </h3>

    <?=
        GridView::widget(
            [
                'dataProvider' => $ingredientDataProvider,
                'filterModel' => $ingredientSearchModel,
                'columns' => [
                    'name',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '<div class="text-center">{add-ingredient}</div>',
                        'buttons' => [
                            'add-ingredient' => function ($url, $ingredientModel, $key) use ($model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-plus"></span>',
                                    $url . '&dishID=' . $model->id,
                                    [
                                        'title' => Yii::t('app', 'Add Ingredient'),
                                        'data-method' => 'post',
                                        'data-pjax' => '0',
                                    ]
                                );
                            },
                        ],
                        'options' => [
                            'width' => 50,
                        ],
                    ],
                ],
            ]
        );
    ?>

    <?php Pjax::end(); ?>

</div>
