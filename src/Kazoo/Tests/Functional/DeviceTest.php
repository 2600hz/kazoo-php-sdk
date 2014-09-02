<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class DeviceTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateDevice() {
        $device = $this->getSDK()->Account()->Device();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Device", $device);
        $this->assertTrue((strlen($device->getId()) == 0));

        $device->name = "SDK Create Test " . rand(100, 1000);
        $device->save();

        $this->assertTrue((strlen($device->getId()) > 0));
        return $device->getId();
    }

    /**
     * @test
     * @depends testCreateDevice
     */
    public function testFetchDevice($device_id) {
        $device = $this->getSDK()->Account()->Device($device_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Device", $device);
        $this->assertTrue((strlen($device->getId()) > 0));
        $this->assertEquals($device->getId(), $device_id);

        return $device;
    }

    /**
     * @test
     * @depends testFetchDevice
     */
    public function testUpdateDevice($device) {
        $device_id = $device->getId();
        $current_name = $device->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $device->name = $new_name;
        $device->save();

        // Make sure we didn't create a new device
        $this->assertEquals($device->getId(), $device_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($device->name, $new_name);

        // Fetch it from the database again to make sure
        $device->fetch();
        $this->assertEquals($device->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateDevice
     */
    public function testListingDevices($device_id) {
        $devices = $this->getSDK()->Account()->Devices();

        $device = null;
        foreach($devices as $element) {
            if ($element->id == $device_id) {
                $device = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Device", $device);
        $this->assertTrue((strlen($device->getId()) > 0));
        $this->assertEquals($device->getId(), $device_id);

        return $device->name;
    }

    /**
     * @test
     * @depends testCreateDevice
     * @depends testListingDevices
     */
    public function testFilteredListingDevices($device_id, $device_name) {
        $filter = array('filter_name' => $device_name);
        $devices = $this->getSDK()->Account()->Devices($filter);

        $this->assertTrue(count($devices) == 1);

        $element = $devices->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $device_id);

        $filter = array('filter_name' => 'no-such-device');
        $devices = $this->getSDK()->Account()->Devices($filter);

        $this->assertTrue(count($devices) == 0);
    }

    /**
     * @test
     * @depends testFetchDevice
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildDevice($device) {
        $device_id = $device->getId();

        $this->assertTrue((strlen($device->getId()) > 0));

        $device->remove();

        $this->assertTrue((strlen($device->getId()) == 0));

//        $device($device_id)->fetch();
    }

    /**
     * @test
     */
    public function testDevicesStatus() {
        $status = $this->getSDK()->Account()->Devices()->status();
        $this->assertInstanceOf("\\Kazoo\\Api\\Collection\\Devices", $status);
    }
}