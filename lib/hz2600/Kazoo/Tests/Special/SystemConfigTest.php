<?php

namespace Kazoo\Tests\Special;

use \Exception;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group special
 */
class SystemConfigTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testListSystemConfig(){
        $system_config = $this->getSDK()->SystemConfigs();
        $this->assertInstanceOf("\\Kazoo\\Api\\Collection\\SystemConfigs", $system_config);
    }

    /**
     * @test
     */
    public function testGetEcallmgr(){
        $ecallmgr = $this->getSDK()->SystemConfig('ecallmgr');
        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\SystemConfig", $ecallmgr);
        return $ecallmgr;
    }

    /**
     * @test
     * @depends testGetEcallmgr
     */
    public function testUpdateEcallmgr($ecallmgr){
        $ecallmgr->test = true;
        $ecallmgr->save();
        $new_ecallmgr = $this->getSDK()->SystemConfig('ecallmgr');
        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\SystemConfig", $new_ecallmgr);
        $this->assertTrue($new_ecallmgr->test);
    }

}
