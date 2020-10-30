<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Ingredient;
use common\models\DishIngredient;

/**
 * IngredientSearch represents the model behind the search form of `common\models\Ingredient`.
 */
class IngredientSearch extends Ingredient
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_visible'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Ingredient::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_visible' => $this->is_visible,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    /**
     * Search ingredients for specified dish
     *
     * @param int   $dishID
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchWithDish($dishID, $params)
    {
        $query = Ingredient::find()
            ->joinWith('dishIngredients')
            ->andWhere(['dish_ingredient.dish_id' => $dishID]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_visible' => $this->is_visible,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    /**
     * Search ingredients not conataing in specified dish
     *
     * @param int   $dishID
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchWithoutDish($dishID, $params)
    {
        $existingDishIngredients = DishIngredient::findAll(['dish_id' => $dishID]);

        $ingrediantIDs = array_map(
            function ($item) {
                return $item->ingredient_id;
            },
            $existingDishIngredients
        );

        $query = Ingredient::find()
            ->andWhere(['not in', 'id', $ingrediantIDs]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_visible' => $this->is_visible,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    /**
     * Search ingredients selected by user
     *
     * @param int   $userID
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchWithUser($userID, $params)
    {
        $query = Ingredient::find()
            ->joinWith('userIngredients')
            ->andWhere(['user_ingredient.user_id' => $userID])
            ->andWhere(['ingredient.is_visible' => 1]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 5,
                ],
            ]
        );

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_visible' => $this->is_visible,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    /**
     * Search ingredients not selected by user
     *
     * @param int   $userID
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchWithoutUser($userID, $params)
    {
        $choosenUserIngredients = UserIngredient::findAll(['user_id' => $userID]);

        $ingrediantIDs = array_map(
            function ($item) {
                return $item->ingredient_id;
            },
            $choosenUserIngredients
        );

        $query = Ingredient::find()
            ->andWhere(['not in', 'id', $ingrediantIDs])
            ->andWhere(['is_visible' => 1]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 5,
                ],
            ]
        );

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_visible' => $this->is_visible,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
