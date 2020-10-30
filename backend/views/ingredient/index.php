<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\IngredientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ingredients');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="ingredient-index">

    <h3 class="text-muted">
        <?= Html::encode($this->title) ?>
    </h3>

    <p>
        <?= Html::a(Yii::t('app', 'Create Ingredient'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
        GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'name',
                    [
                        'attribute' => 'is_visible',
                        'filter' => [
                            '1' => Yii::t('app', 'Yes'),
                            '0' => Yii::t('app', 'No')
                        ],
                        'content' => function ($model) {
                            return $model->is_visible
                                ? Yii::t('app', 'Yes')
                                : Yii::t('app', 'No');
                        },
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '<div class="text-center">{details} &nbsp; {update} &nbsp; {delete}</div>',
                        'buttons' => [
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
