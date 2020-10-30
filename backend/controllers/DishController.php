<?php

namespace backend\controllers;

use Yii;
use common\models\Dish;
use common\models\DishSearch;
use common\models\IngredientSearch;
use common\models\DishIngredient;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * DishController implements the CRUD actions for Dish model.
 */
class DishController extends Controller
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
     * Lists all Dish models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DishSearch();
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
     * Creates a new Dish model.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Dish();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render(
            'create', [
                'model' => $model,
            ]
        );
    }

    /**
     * Updates an existing Dish model.
     *
     * @param integer $id Dish ID
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

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
     * Deletes an existing Dish model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id Dish ID
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
     * Lists Dish ingredients.
     *
     * @param integer $id Dish ID
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionIngredient($id)
    {
        return $this->_renderIngredients($id);
    }

    /**
     * Add specified ingredient to the dish.
     *
     * @param int $id     Ingredient ID
     * @param int $dishID Dish ID
     *
     * @return mixed
     */
    public function actionAddIngredient($id, $dishID)
    {
        $dishIngredients = DishIngredient::findAll(['dish_id' => $dishID]);

        if (count($dishIngredients) === 5) {
            Yii::$app->session->setFlash(
                'error',
                Yii::t('app', 'Cannot add more than five ingredients')
            );
            return $this->_renderIngredients($dishID);
        }

        $model = new DishIngredient();

        $model->ingredient_id = $id;
        $model->dish_id = $dishID;

        $model->save();

        return $this->_renderIngredients($dishID);
    }

    /**
     * Delete specified ingredient from the dish.
     *
     * @param int $id     Ingredient ID
     * @param int $dishID Dish ID
     *
     * @return mixed
     */
    public function actionDeleteIngredient($id, $dishID)
    {
        DishIngredient::deleteAll(
            [
                'dish_id' => $dishID,
                'ingredient_id' => $id,
            ]
        );

        return $this->_renderIngredients($dishID);
    }

    /**
     * Finds the Dish model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id Dish ID
     *
     * @return Dish the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dish::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Render dish ingredients
     *
     * @param int $dishID Dish ID
     *
     * @return mixed
     */
    private function _renderIngredients($dishID)
    {
        $dishIngredientSearchModel = new IngredientSearch();
        $dishIngredientDataProvider = $dishIngredientSearchModel->searchWithDish(
            $dishID,
            []
        );

        $ingredientSearchModel = new IngredientSearch();
        $ingredientDataProvider = $ingredientSearchModel->searchWithoutDish(
            $dishID,
            Yii::$app->request->queryParams
        );

        return $this->render(
            'ingredients',
            [
                'model' => $this->findModel($dishID),
                'dishIngredientDataProvider' => $dishIngredientDataProvider,
                'ingredientSearchModel' => $ingredientSearchModel,
                'ingredientDataProvider' => $ingredientDataProvider,
            ]
        );
    }
}
