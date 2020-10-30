<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dishes common\models\Dish */
/* @var $userIngredientSearchModel common\models\IngredientSearch */
/* @var $userIngredientDataProvider yii\data\ActiveDataProvider */
/* @var $ingredientSearchModel common\models\IngredientSearch */
/* @var $ingredientDataProvider yii\data\ActiveDataProvider */
/* @var $dishSearchModel common\models\dishSearch */
/* @var $dishDataProvider yii\data\ActiveDataProvider */

$this->title = 'My Yii Application';

?>
<div class="site-index">

    <h4 class="text-muted">
        <?= Yii::t('app', 'Selected ingredients for dish cooking:') ?>
    </h4>

    <?=
        GridView::widget(
            [
                'dataProvider' => $userIngredientDataProvider,
                'layout' => "{items}",
                'emptyText' => Yii::t('app', 'Nothing selected yet'),
                'columns' => [
                    'name',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '<div class="text-center">{remove-ingredient}</div>',
                        'buttons' => [
                            'remove-ingredient' => function ($url, $ingredientModel, $key) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-remove"></span>',
                                    $url,
                                    [
                                        'title' => Yii::t('app', 'Remove Ingredient'),
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

    <h4 class="text-muted">
        <?= Yii::t('app', 'Choose ingredient to add:') ?>
    </h4>

    <?=
        GridView::widget(
            [
                'dataProvider' => $ingredientDataProvider,
                'filterModel' => $ingredientSearchModel,
                'layout' => "{items}\n{pager}",
                'columns' => [
                    'name',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '<div class="text-center">{choose-ingredient}</div>',
                        'buttons' => [
                            'choose-ingredient' => function ($url, $ingredientModel, $key) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-plus"></span>',
                                    $url,
                                    [
                                        'title' => Yii::t('app', 'Choose Ingredient'),
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

    <?php if (isset($dishDataProvider)) : ?>

    <h4 class="text-muted">
        <?= Yii::t('app', 'Suitable dishes:') ?>
    </h4>

    <?=
        GridView::widget(
            [
                'dataProvider' => $dishDataProvider,
                'filterModel' => $dishSearchModel,
                'layout' => "{items}\n{pager}",
                'columns' => [
                    'name',
                ],
            ]
        );
    ?>

    <?php endif; ?>

</div>
