<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dish_ingredient".
 *
 * @property int $id
 * @property int $dish_id
 * @property int $ingredient_id
 *
 * @property Dish $dish
 * @property Ingredient $ingredient
 */
class DishIngredient extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dish_ingredient';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dish_id', 'ingredient_id'], 'required'],
            [['dish_id', 'ingredient_id'], 'integer'],
            [['dish_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dish::class, 'targetAttribute' => ['dish_id' => 'id']],
            [['ingredient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ingredient::class, 'targetAttribute' => ['ingredient_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dish_id' => Yii::t('app', 'Dish ID'),
            'ingredient_id' => Yii::t('app', 'Ingredient ID'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            // Ингредиент скрытый, обрабатывааем это
            // P.S. Вопрос кстати, допустимо ли это, добавлять в админке уже скрытые ингредиенты в блюда?
            if ($this->ingredient->is_visible === 0) {
                $dish = $this->dish;
                $dish->hidden_components_counter = $dish->hidden_components_counter + 1;
                $dish->save();
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritDoc
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        if ($this->ingredient->is_visible === 0) {    // Удаляем связь со скрытым ингредиентом, поэтому уменьшаем счетчик
            $dish = $this->getDish();
            $dish->hidden_components_counter = $dish->hidden_components_counter - 1;
            $dish->save();
        }

        return true;
    }

    /**
     * Gets query for [[Dish]].
     *
     * @return \yii\db\ActiveQuery|DishQuery
     */
    public function getDish()
    {
        return $this->hasOne(Dish::class, ['id' => 'dish_id']);
    }

    /**
     * Gets query for [[Ingredient]].
     *
     * @return \yii\db\ActiveQuery|IngredientQuery
     */
    public function getIngredient()
    {
        return $this->hasOne(Ingredient::class, ['id' => 'ingredient_id']);
    }

    /**
     * {@inheritdoc}
     * @return DishIngredientQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DishIngredientQuery(get_called_class());
    }
}
