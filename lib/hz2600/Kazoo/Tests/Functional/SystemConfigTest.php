<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */

class SystemConfigTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testGetSystemConfig(){
        $systemconfig = $this->getSDK()->Account()->SystemConfig();
        $this->assertInstanceOf("\\Kazoo\\Api\\Collection\\SystemConfig", $systemconfig);

    }

}