<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use backend\fixtures\AdminFixture;

/**
 * Class LoginCest
 */
class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'admin' => [
                'class' => AdminFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ]
        ];
    }

    /**
     * @param FunctionalTester $I
     */
    public function loginAdmin(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
        $I->fillField('Username', 'testadmin');
        $I->fillField('Password', 'password_1');
        $I->click('login-button');

        $I->see('Logout (testadmin)', 'form button[type=submit]');
        $I->dontSeeLink('Login');
        $I->dontSeeLink('Signup');
    }
}
