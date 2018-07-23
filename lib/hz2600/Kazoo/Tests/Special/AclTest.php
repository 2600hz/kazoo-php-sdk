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
        $acls = $this->getSDK()->Account()->Acls();

        $this->assertInstanceOf("\\Kazoo\\Api\\Collection\\Acls", $acls);
        $this->assertTrue((count($acls) > 0));
    }
}
