<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[UserIngredient]].
 *
 * @see UserIngredient
 */
class UserIngredientQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserIngredient[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserIngredient|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
