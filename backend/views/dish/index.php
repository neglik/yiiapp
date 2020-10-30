<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\DishSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Dishes');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="dish-index">

    <h3 class="text-muted">
        <?= Html::encode($this->title) ?>
    </h3>

    <p>
        <?= Html::a(Yii::t('app', 'Create Dish'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
        GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'name',
                        'contentOptions' => function ($model) {
                            return [
                                'class' => $model->hidden_components_counter > 0 ? 'text-muted' : ''
                            ];
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '<div class="text-center">{ingredient} &nbsp; {update} &nbsp; {delete}</div>',
                        'buttons' => [
                            'ingredient' => function ($url, $model, $key) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-menu-hamburger"></span>',
                                    $url,
                                    [
                                        'title' => Yii::t('app', 'Ingredients'),
                                        'data-pjax' => '0',
                                    ]
                                );
                            },
                            'update' => function ($url, $model, $key) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-edit"></span>',
                                    $url,
                                    [
                                        'title' => Yii::t('app', 'Update'),
                                        'data-pjax' => '0',
                                    ]
                                );
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-remove"></span>',
                                    $url,
                                    [
                                        'title' => Yii::t('app', 'Delete'),
                                        'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                        'data-method' => 'post',
                                        'data-pjax' => '0',
                                    ]
                                );
                            },
                        ],
                    ],
                ],
            ]
        );
    ?>

    <?php Pjax::end(); ?>

</div>
