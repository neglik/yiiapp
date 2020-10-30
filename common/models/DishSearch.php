<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Dish;

/**
 * DishSearch represents the model behind the search form of `common\models\Dish`.
 */
class DishSearch extends Dish
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
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
        $query = Dish::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query and user ingredients applied
     *
     * @param ActiveQueryInterface $userIngredientsQuery User ingredients
     * @param array                $params               Search params
     *
     * @return ActiveDataProvider
     */
    public function searchWithUserIngredients($userIngredientsQuery, $params)
    {
        $userIngredients = $userIngredientsQuery->all();
        $userIngredientCount = count($userIngredients);

        $userIngredientIDs = array_map(
            function ($item) {
                return $item->id;
            },
            $userIngredients
        );

        // Сначала ищем точное соответствие
        $query = Dish::find()
            ->joinWith('dishIngredients')
            ->andWhere(['dish.hidden_components_counter' => 0])
            ->andWhere(['in', 'dish_ingredient.ingredient_id', $userIngredientIDs])
            ->groupBy('dish.id')
            ->having("COUNT(dish.id) = {$userIngredientCount}");

        $dishes = $query->all();

        if (count($dishes) === 0) {
            $query = Dish::find()
                ->joinWith('dishIngredients')
                ->andWhere(['dish.hidden_components_counter' => 0])
                ->andWhere(['in', 'dish_ingredient.ingredient_id', $userIngredientIDs])
                ->groupBy('dish.id')
                ->having('COUNT(dish.id) > 1')     // Нам интересуют только два и более совпадения
                ->orderBy('COUNT(dish.id) DESC');
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
