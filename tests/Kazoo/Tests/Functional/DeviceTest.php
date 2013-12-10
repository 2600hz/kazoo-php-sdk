<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Resources\Device;

/**
 * @group functional
 */
class DeviceTest extends TestCase {

    /**
     * @test
     */
    public function testEmptyShell() {
        $device = $this->client->api('accounts')->devices()->new();
        $this->assertObjectHasAttribute('name', $device);
        $this->assertObjectHasAttribute('owner_id', $device);
        $this->assertObjectHasAttribute('outbound_flags', $device);
        $this->assertObjectHasAttribute('suppress_unregister_notifications', $device);
        $this->assertObjectHasAttribute('caller_id', $device);
        $this->assertInstanceOf("Kazoo\\Api\\Resources\\Device", $device);
    }

}
