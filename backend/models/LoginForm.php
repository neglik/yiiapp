<?php

namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $changePassword = false;
    public $newPassword;
    public $confirmNewPassword;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['rememberMe', 'changePassword'], 'boolean'],
            ['password', 'validatePassword'],
            [['newPassword', 'confirmNewPassword'], 'safe'],
            [['newPassword', 'confirmNewPassword'], 'validateNewPassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'rememberMe' => Yii::t('app', 'Remeber Me'),
            'changePassword' => Yii::t('app', 'Change Password'),
            'newPassword' => Yii::t('app', 'New Password'),
            'confirmNewPassword' => Yii::t('app', 'Confirm New Password'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute The attribute currently being validated
     * @param array  $params    The additional name-value pairs given in the rule
     *
     * @return void
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Validates new password.
     *
     * @param string $attribute The attribute currently being validated
     * @param array  $params    The additional name-value pairs given in the rule
     *
     * @return void
     */
    public function validateNewPassword($attribute, $params)
    {
        if ($this->changePassword) {
            if ($this->newPassword !== $this->confirmNewPassword) {
                $this->addError($attribute, 'Password and confirmation do not match');
            } else if (strlen($this->newPassword) < 8) {
                $this->addError($attribute, 'Password must contain at least 8 characters');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            if ($this->changePassword) {
                $user = $this->getUser();
                $user->password_hash = Yii::$app->security->generatePasswordHash($this->newPassword);
                $user->save();
            }

            return Yii::$app->user->login(
                $this->getUser(),
                $this->rememberMe ? 3600 * 24 * 30 : 0
            );
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return Admin|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Admin::findByUsername($this->username);
        }

        return $this->_user;
    }
}
