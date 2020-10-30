<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dish".
 *
 * @property int $id
 * @property string $name
 * @property int $hidden_components_counter
 *
 * @property DishIngredient[] $dishIngredients
 * @property Ingredient[] $ingredients
 */
class Dish extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dish';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            ['hidden_components_counter', 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    public static function increaseHiddenCounter($ingredientID)
    {
        Yii::$app->db->createCommand(
            "UPDATE dish SET dish.hidden_components_counter = dish.hidden_components_counter + 1 "
            . "WHERE dish.id IN ("
            . "   SELECT dish_ingredient.dish_id FROM dish_ingredient WHERE dish_ingredient.ingredient_id={$ingredientID}"
            . ")"
        )->execute();
    }

    public static function decreaseHiddenCounter($ingredientID)
    {
        Yii::$app->db->createCommand(
            "UPDATE dish SET dish.hidden_components_counter = dish.hidden_components_counter - 1 "
            . "WHERE dish.id IN ("
            . "   SELECT dish_ingredient.dish_id FROM dish_ingredient WHERE dish_ingredient.ingredient_id={$ingredientID}"
            . ")"
        )->execute();
    }

    /**
     * Gets query for [[DishIngredients]].
     *
     * @return \yii\db\ActiveQuery|DishIngredientQuery
     */
    public function getDishIngredients()
    {
        return $this->hasMany(DishIngredient::class, ['dish_id' => 'id']);
    }

    /**
     * Gets query for [[Ingredients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIngredients()
    {
        return $this->hasMany(Ingredient::class, ['id' => 'ingredient_id'])
            ->viaTable('dish_ingredient', ['dish_id' => 'id']);
    }
}
