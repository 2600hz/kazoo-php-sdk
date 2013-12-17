<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Device;

/**
 * @group functional
 */
class DeviceTest extends TestCase {

    /**
     * @test
     */
    public function testEmptyShell() {
        try {
            $device = $this->client->accounts()->devices()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Device", $device);
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    public function testRetriveAll() {
        $devices = $this->client->accounts()->devices()->retrieve();
        $this->assertGreaterThan(0, count($devices->data));
    }

    public function testCreateDevice() {
        $shellDevice = $this->client->accounts()->devices()->new();
        $num = substr(number_format(time() * rand(),0,'',''),0,4);
        $shellDevice->name = "Test Device #" . $num;
        $shellDevice->sip->password = substr(number_format(time() * rand(),0,'',''),0,10);
        $shellDevice->sip->username = "testdevice".$num;
        $newDevice = $this->client->accounts()->devices()->create($shellDevice);
        $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Device", $newDevice);
        $this->assertObjectHasAttribute('id', $newDevice);
    }

}
