<?php

namespace backend\controllers;

use Yii;
use backend\models\Admin;
use backend\models\AdminSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * AdminController implements the CRUD actions for Admin model.
 */
class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Admin models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Creates a new Admin model.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Admin();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash(
                'success',
                Yii::t(
                    'app',
                    'Admnistrator {username} with password {password} has been created successfully.',
                    ['username' => $model->username, 'password' => Admin::INITIAL_PASSWORD]
                )
            );

            return $this->redirect(['index']);
        }

        return $this->render(
            'create', [
                'model' => $model,
            ]
        );
    }

    /**
     * Updates an existing Admin model.
     *
     * @param integer $id Admin ID
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->status === Admin::STATUS_DELETED) {
            throw new NotFoundHttpException(Yii::t('app', 'Cannon find requested editable admin.'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render(
            'update', [
                'model' => $model,
            ]
        );
    }

    /**
     * Reset password for admin with specified id.
     *
     * @param integer $id Admir ID
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionResetpassword($id)
    {
        $model = $this->findModel($id);

        if ($model->status === Admin::STATUS_DELETED) {
            throw new NotFoundHttpException(Yii::t('app', 'Cannon find requested editable admin.'));
        }

        $model->password_hash = Yii::$app->security->generatePasswordHash(Admin::INITIAL_PASSWORD);

        if ($model->save()) {
            Yii::$app->session->setFlash(
                'success',
                Yii::t(
                    'app',
                    'Password reset to {password}',
                    ['password' => Admin::INITIAL_PASSWORD]
                )
            );
            return $this->redirect(['index']);
        }

        return $this->render(
            'update', [
                'model' => $model,
            ]
        );
    }

    /**
     * Deletes an existing Admin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id Admin ID
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id Admin ID
     *
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
