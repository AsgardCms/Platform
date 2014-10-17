<?php namespace Modules\User\Tests;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Config;
use Modules\Core\Tests\BaseTestCase;

class UserUrlTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $user = Sentinel::findById(4);
        Sentinel::login($user);
    }

    /** @test */
    public function userIndexShouldBeAccessible()
    {
        $uri = '/' . Config::get('core::core.admin-prefix') . '/users';

        $this->checkResponseIsOkAndContains(['GET', $uri], 'h1:contains("Users")');
    }


    /** @test */
    public function userEditShouldBeAccessible()
    {
        $uri = '/' . Config::get('core::core.admin-prefix') . '/users/4/edit';

        $this->checkResponseIsOkAndContains(['GET', $uri], 'h1:contains("Edit User")');
    }

    /** @test */
    public function userCreateShouldBeAccessible()
    {
        $uri = '/' . Config::get('core::core.admin-prefix') . '/users/create';

        $this->checkResponseIsOkAndContains(['GET', $uri], 'h1:contains("New User")');
    }

    /** @test */
    public function roleIndexShouldBeAccessible()
    {
        $uri = '/' . Config::get('core::core.admin-prefix') . '/roles';

        $this->checkResponseIsOkAndContains(['GET', $uri], 'h1:contains("Roles")');
    }

    /** @test */
    public function roleCreateShouldBeAccessible()
    {
        $uri = '/' . Config::get('core::core.admin-prefix') . '/roles/create';

        $this->checkResponseIsOkAndContains(['GET', $uri], 'h1:contains("New Role")');
    }

    /** @test */
    public function roleEditShouldBeAccessible()
    {
        $uri = '/' . Config::get('core::core.admin-prefix') . '/roles/6/edit';

        $this->checkResponseIsOkAndContains(['GET', $uri], 'h1:contains("Updating Role")');
    }
}
