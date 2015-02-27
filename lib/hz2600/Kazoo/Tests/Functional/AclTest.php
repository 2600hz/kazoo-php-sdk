<?php

namespace Kazoo\Tests\Functional;

use \Exception;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
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

        // $acls->fetch();
    }
}