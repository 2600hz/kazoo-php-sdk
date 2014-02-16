<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Device;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class DeviceTest extends \PHPUnit_Framework_TestCase {

    protected $client;

    public function setUp() {

        $username = 'bwann';
        $password = '12341234';
        $sipRealm = 'sip.benwann.com';
        $options = array();
        $options["base_url"] = "http://192.168.56.111:8000";

        // You have to specify authentication here to run full suite

        try {
            $this->client = new \Kazoo\Client($username, $password, $sipRealm, $options);
        } catch (ApiLimitExceedException $e) {
            $this->markTestSkipped('API limit reached. Skipping to prevent unnecessary failure.');
        } catch (RuntimeException $e) {
            if ('Requires authentication' == $e->getMessage()) {
                $this->markTestSkipped('Test requires authentication. Skipping to prevent unnecessary failure.');
            }
        }
    }

    /**
     * @test
     */
    public function testCreateEmptyDevice() {

        try {
            $device = $this->client->accounts()->devices()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Device", $device);

            return $device;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyDevice
     */
    public function testCreateDevice($device) {

        try {
            $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);

            $device->name = "Test Device #" . $num;
            $device->sip->password = substr(number_format(time() * rand(), 0, '', ''), 0, 10);
            $device->sip->username = "testdevice" . $num;
            $device->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Device", $device);
            $this->assertTrue((strlen($device->id) > 0));

            return $device->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateDevice
     */
    public function testRetrieveDevice($device_id) {

        try {
            $device = $this->client->accounts()->devices()->retrieve($device_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Device", $device);
            $this->assertTrue((strlen($device->id) > 0));
            return $device;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveDevice
     */
    public function testUpdateDevice($device) {

        try {
            $device->name = "Updated: " . $device->name;
            $device->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Device", $device);
            $this->assertTrue((strlen($device->id) > 0));

            return $device;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testUpdateDevice
     */
    public function testRetrieveAllAndUpdateOne($search_device) {
        
        try {
            
            $devices = $this->client->accounts()->devices()->retrieve();
            foreach($devices as $device){
                if($device->id == $search_device->id){
                    $search_device->name = "Updated: " . $search_device->name;
                    $search_device->save();
                }
            }
            $this->assertGreaterThan(0, count($devices));
            return $search_device;
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveAllAndUpdateOne
     */
    public function testDeleteDevice($device) {

        try {
            $device->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
