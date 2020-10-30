<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use common\models\IngredientSearch;
use common\models\DishSearch;
use common\models\UserIngredient;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => [
                            'about',
                            'signup',
                            'login',
                            'request-password-reset',
                            'resend-verification-email',
                            'reset-password',
                            'verify-email'
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [
                            'logout',
                            'signup',
                            'index',
                            'about',
                            'choose-ingredient',
                            'remove-ingredient'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays main epage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $userID = Yii::$app->user->getId();

        return $this->_renderUserOrder($userID);
    }

    /**
     * Add user ingredient
     *
     * @param int $id Ingredient ID
     *
     * @return mixed
     */
    public function actionChooseIngredient($id)
    {
        $userID = Yii::$app->user->getId();

        $userIngredients = UserIngredient::findAll(['user_id' => $userID]);

        if (count($userIngredients) === 5) {
            Yii::$app->session->setFlash(
                'error',
                Yii::t('app', 'Cannot choose more than five ingredients')
            );
            return $this->_renderUserOrder($userID);
        }

        $model = new UserIngredient();
        $model->user_id = $userID;
        $model->ingredient_id = $id;
        $model->save();

        return $this->_renderUserOrder($userID);
    }

    /**
     * Remove user ingredient
     *
     * @param int $id Ingredient ID
     *
     * @return mixed
     */
    public function actionRemoveIngredient($id)
    {
        $userID = Yii::$app->user->getId();

        UserIngredient::deleteAll(
            [
                'user_id' => $userID,
                'ingredient_id' => $id,
            ]
        );

        return $this->_renderUserOrder($userID);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render(
                'login',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Thank you for registration. Please check your inbox for verification email.'));
            return $this->goHome();
        }

        return $this->render(
            'signup',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, we are unable to reset password for the provided email address.'));
            }
        }

        return $this->render(
            'requestPasswordResetToken',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Resets password.
     *
     * @param string $token Token
     *
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'New password saved.'));

            return $this->goHome();
        }

        return $this->render(
            'resetPassword',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Verify email address
     *
     * @param string $token Token
     *
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Your email has been confirmed!'));
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, we are unable to verify your account with provided token.'));
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));
                return $this->goHome();
            }
            Yii::$app->session->setFlash(
                'error',
                Yii::t('app', 'Sorry, we are unable to resend verification email for the provided email address.')
            );
        }

        return $this->render(
            'resendVerificationEmail',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Reneder user ingredients and resulting dishes
     *
     * @param int $userID User ID
     *
     * @return mixed
     */
    private function _renderUserOrder($userID)
    {
        $userIngredientSearchModel = new IngredientSearch();
        $userIngredientDataProvider = $userIngredientSearchModel->searchWithUser(
            $userID,
            []
        );

        $ingredientSearchModel = new IngredientSearch();
        $ingredientDataProvider = $ingredientSearchModel->searchWithoutUser(
            $userID,
            Yii::$app->request->queryParams
        );

        $userIngredients = $userIngredientDataProvider->query->all();
        if (count($userIngredients) < 2) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Choose more ingredients'));
            $dishSearchModel = null;
            $dishDataProvider = null;
        } else {
            $dishSearchModel = new DishSearch();
            $dishDataProvider = $dishSearchModel->searchWithUserIngredients(
                $userIngredientDataProvider->query,
                Yii::$app->request->queryParams
            );
        }

        return $this->render(
            'index',
            [
                'userIngredientSearchModel' => $userIngredientSearchModel,
                'userIngredientDataProvider' => $userIngredientDataProvider,
                'ingredientSearchModel' => $ingredientSearchModel,
                'ingredientDataProvider' => $ingredientDataProvider,
                'dishSearchModel' => $dishSearchModel,
                'dishDataProvider' => $dishDataProvider,
            ]
        );
    }
}
