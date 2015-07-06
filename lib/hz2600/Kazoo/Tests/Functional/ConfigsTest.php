<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class ConfigTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateConfig() {
        $config = $this->getSDK()->Account()->Config();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Config", $config);
        $this->assertTrue((strlen($config->getId()) == 0));

        $config->name = "SDK Create Test " . rand(100, 1000);
        $id_name = "entity".rand(10,99);
        $config->save($id_name);

        $this->assertTrue((strlen($config->getId()) > 0));
        return $config->getId();
    }

    /**
     * @test
     * @depends testCreateConfig
     */
    public function testFetchConfig($config_id) {
        $config = $this->getSDK()->Account()->Config($config_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Config", $config);
        $this->assertTrue((strlen($config->getId()) > 0));
        $this->assertEquals($config->getId(), $config_id);

        return $config;
    }

    /**
     * @test
     * @depends testFetchConfig
     */
    public function testUpdateConfig($config) {
        $config_id = $config->getId();
        
        $current_name = $config->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $config->name = $new_name;
        $config->save();

        // Make sure we didn't create a new config
        $this->assertEquals($config->getId(), $config_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($config->name, $new_name);

        // Fetch it from the database again to make sure
        $config->fetch();
        $this->assertEquals($config->name, $new_name);
    }

    /**
     * @test
     * @depends testFetchConfig
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildConfig($config) {
        $config_id = $config->getId();
        $this->assertTrue((strlen($config->getId()) > 0));

        $config->remove();

        $this->assertTrue((strlen($config->getId()) == 0));

        //$config($config_id)->fetch();
    } 

}
