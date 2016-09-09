<?php

namespace Modules\User\Tests\Permissions;

use Modules\Core\Tests\BaseTestCase;
use Modules\User\Permissions\PermissionManager;

class PermissionManagerTest extends BaseTestCase
{
    /**
     * @test
     */
    public function it_should_clean_permissions()
    {
        $input = [
            'permission1' => '1',
            'permission2' => '1',
            'permission3' => '-1',
            'permission4' => '-1',
            'permission5' => '0',
            'permission6' => '0',
        ];

        $expected = [
            'permission1' => true,
            'permission2' => true,
            'permission3' => false,
            'permission4' => false,
        ];

        $manager = new PermissionManager();

        $actual = $manager->clean($input);

        $this->assertSame($expected, $actual, 'The PermissionManager should clean the permissions and fix their states.');
    }
}
