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
        $options["log_type"] = "file";
        $options["log_file"] = "/var/log/kazoo-sdk.log";

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

//    public function testRetriveAll() {
//        $devices = $this->client->accounts()->devices()->retrieve();
//        $this->assertGreaterThan(0, count($devices->data));
//    }

    /**
     * @test
     */
    public function testCreateEmptyShell() {
        try {
            $device = $this->client->accounts()->devices()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Device", $device);
            
            return $device;
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyShell
     */
    public function testCreateDevice($device) {
        $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
        
        $device->name = "Test Device #" . $num;
        $device->sip->password = substr(number_format(time() * rand(), 0, '', ''), 0, 10);
        $device->sip->username = "testdevice" . $num;
        $this->client->accounts()->devices()->create($device);

        $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Device", $device);
        $this->assertObjectHasAttribute('id', $device);

        return $device->id;
    }
//
//    /**
//     * @test
//     * @depends testCreateDevice
//     */
//    public function testRetriveDevice($device_id) {
//        $device = $this->client->accounts()->devices()->retrieve($device_id);
//        $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Device", $device);
//        $this->assertObjectHasAttribute('id', $device);
//    }
//
//    /**
//     * @test
//     * @depends testCreateDevice
//     */
//    public function testUpdateOne($device_id) {
//        $device = $this->client->accounts()->devices()->retrieve($device_id);
//        $device->name = "Updated Name";
//        $device->save();
//
//        $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Device", $device);
//        $this->assertObjectHasAttribute('id', $device);
//    }
//
//    /**
//     * @test
//     * @depends testCreateDevice
//     */
//    public function testDeleteOne($device_id) {
//        $device = $this->client->accounts()->devices()->retrieve($device_id);
//        $device->delete();
//        $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
//    }

}
