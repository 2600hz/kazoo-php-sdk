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
        return $system_config;
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

    /**
     * @test
     */
    public function testGetFax(){
        $fax = $this->getSDK()->SystemConfig('fax');
        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\SystemConfig", $fax);
        return $fax;
    }

    /**
     * @test
     * @depends testGetFax
     */
    public function testUpdateFax($fax){
        $fax->test = true;
        $fax->save();
        $new_fax = $this->getSDK()->SystemConfig('fax');
        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\SystemConfig", $new_fax);
        $this->assertTrue($new_fax->test);
    }

    /**
     * @test
     */
    public function testAddPerNodeFaxOverride(){
        $fax = $this->getSDK()->SystemConfig('fax');
        $fax->test_override = true;
        $fax->save("/whistle_apps@test.test.com");
        $new_fax = $this->getSDK()->SystemConfig('fax')->fetch("/whistle_apps@test.test.com");
        $this->assertTrue($new_fax->test);
        return $new_fax;
    }

    /**
     * @test
     * @depends testAddPerNodeFaxOverride
     */
    public function testUpdatePerNodeFaxOverride($fax){
        $fax->test_update_override = true;
        $fax->save("/whistle_apps@test.test.com");
        $this->assertTrue($fax->test_update_override);
        $new_fax = $this->getSDK()->SystemConfig('fax')->fetch("/whistle_apps@test.test.com");
        $this->assertTrue($new_fax->test_update_override);
    }

    /**
     * @test
     * @depends testAddPerNodeFaxOverride
     */
    public function testDeletePerNodeFaxOverride($fax){
        $fax = $this->getSDK()->SystemConfig('fax')->remove("/whistle_apps@test.test.com");
        $new_fax = $this->getSDK()->SystemConfig('fax')->fetch("/whistle_apps@test.test.com");
        $this->assertEmpty($new_fax->test_override);
        return $new_fax;
    }

    /**
     * @test
     * @depends testListSystemConfig
     */
    public function testFetchAllSystemConfigApis($listing){
        foreach ($listing as $element){
            $entity = $element->fetch();
            $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\SystemConfig", $entity);
        }
    }
}
