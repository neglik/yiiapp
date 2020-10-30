<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\Admin;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Administrators');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="admin-index">

    <h3 class="text-muted">
        <?= Html::encode($this->title) ?>
    </h3>

    <p>
        <?= Html::a(Yii::t('app', 'Create Administrator'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
        GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'username',
                    'email',
                    [
                        'attribute' => 'status',
                        'filter' => [
                            Admin::STATUS_ACTIVE => Yii::t('app', 'Active'),
                            Admin::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
                            Admin::STATUS_DELETED => Yii::t('app', 'Deleted'),
                        ],
                        'content' => function ($model) {
                            switch ($model->status) {
                            case Admin::STATUS_ACTIVE:
                                return Yii::t('app', 'Active');
                                break;
                            case Admin::STATUS_INACTIVE:
                                return Yii::t('app', 'Inactive');
                                break;
                            case Admin::STATUS_DELETED:
                                return Yii::t('app', 'Deleted');
                                break;
                            }
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '<div class="text-center">&nbsp; {resetpassword} &nbsp; {update} &nbsp;</div>',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                if ($model->status !== Admin::STATUS_DELETED) {
                                    return Html::a(
                                        '<span class="glyphicon glyphicon-edit"></span>',
                                        $url,
                                        [
                                            'title' => Yii::t('app', 'Update'),
                                            'data-pjax' => '0',
                                        ]
                                    );
                                } else {
                                    return Html::a(
                                        '<span class="text-muted glyphicon glyphicon-edit"></span>',
                                        '#',
                                        [
                                            'title' => Yii::t('app', 'Not applicable'),
                                            'data-pjax' => '0',
                                        ]
                                    );
                                }
                            },
                            'resetpassword' => function ($url, $model, $key) {
                                if ($model->status !== Admin::STATUS_DELETED) {
                                    return Html::a(
                                        '<span class="glyphicon glyphicon-lock"></span>',
                                        $url,
                                        [
                                            'title' => Yii::t('app', 'Reset Password'),
                                            'data-pjax' => '0',
                                        ]
                                    );
                                } else {
                                    return Html::a(
                                        '<span class="text-muted glyphicon glyphicon-lock"></span>',
                                        '#',
                                        [
                                            'title' => Yii::t('app', 'Not applicable'),
                                            'data-pjax' => '0',
                                        ]
                                    );
                                }
                            },
                         ],
                    ],
                ],
            ]
        );
    ?>

    <?php Pjax::end(); ?>

</div>
