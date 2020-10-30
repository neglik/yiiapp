<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ingredient".
 *
 * @property int $id
 * @property string $name
 * @property int $is_visible
 *
 * @property DishIngredient[] $dishIngredients
 * @property UserIngredient[] $userIngredients
 * @property Dishes[] $dishes
 */
class Ingredient extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingredient';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_visible'], 'integer'],
            [['is_visible'], 'default', 'value' => 1],
            ['is_visible', 'filter', 'filter' => function ($value) {
                return (int) $value;
            }],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
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
            'is_visible' => Yii::t('app', 'Is Visible'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert === false) {
            if (isset($changedAttributes['is_visible'])) {
                if ($changedAttributes['is_visible'] === 1) {   // Ингредиент скрывается
                    Dish::increaseHiddenCounter($this->id);
                } else {                                        // Ингредиент показвывается
                    Dish::decreaseHiddenCounter($this->id);
                }
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

        Dish::decreaseHiddenCounter($this->id);     // Ингредиент удаляется, поэтому уменьшаем счетчик у товаров с этим ингредиентом

        return true;
    }

    /**
     * Gets query for [[DishIngredients]].
     *
     * @return \yii\db\ActiveQuery|DishIngredientQuery
     */
    public function getDishIngredients()
    {
        return $this->hasMany(DishIngredient::class, ['ingredient_id' => 'id']);
    }

    /**
     * Gets query for [[Dishes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDishes()
    {
        return $this->hasMany(Dish::class, ['id' => 'dish_id'])
            ->viaTable('dish_ingredient', ['ingredient_id' => 'id']);
    }

    /**
     * Gets query for [[UserIngredients]].
     *
     * @return \yii\db\ActiveQuery|UserIngredientQuery
     */
    public function getUserIngredients()
    {
        return $this->hasMany(UserIngredient::class, ['ingredient_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return IngredientQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new IngredientQuery(get_called_class());
    }
}
