<?php

namespace Kazoo\Tests\Special;

use \Exception;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group special
 */
class AclTest extends FunctionalTest
{
    /**
     * @test
     * @expectedExceptionDisabled \Exception
     */
    public function testGetAcls() {
        $this->markTestIncomplete(
            'This test requires admin account'
        );
        $acls = $this->getSDK()->Account()->Acls();

        $this->assertInstanceOf("\\Kazoo\\Api\\Collection\\Acls", $acls);
        $this->assertTrue((count($acls) > 0));
    }
}
