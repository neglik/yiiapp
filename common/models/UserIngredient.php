<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_ingredient".
 *
 * @property int $id
 * @property int $user_id
 * @property int $ingredient_id
 *
 * @property User $user
 * @property Ingredient $ingredient
 */
class UserIngredient extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_ingredient';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'ingredient_id'], 'required'],
            [['user_id', 'ingredient_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => Yii::t('app', 'User ID'),
            'ingredient_id' => Yii::t('app', 'Ingredient ID'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|DishQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
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
