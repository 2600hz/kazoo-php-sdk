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
        $this->markTestIncomplete(
            'This test requires the system config module to be running'
        );
        $systemconfig = $this->getSDK()->Account()->SystemConfig();
        $this->assertInstanceOf("\\Kazoo\\Api\\Collection\\SystemConfigs", $systemconfig);

    }

}
