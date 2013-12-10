<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Resources\CarrierResource;

/**
 * @group functional
 */
class CarrierResourceTest extends TestCase {

    /**
     * @test
     */
    public function testEmptyShell() {
        $carrier_resource = $this->client->api('accounts')->carrier_resources()->new();
        $this->assertObjectHasAttribute('name', $carrier_resource);
        $this->assertObjectHasAttribute('enabled', $carrier_resource);
        $this->assertObjectHasAttribute('emergency', $carrier_resource);
        $this->assertObjectHasAttribute('grace_period', $carrier_resource);
        $this->assertObjectHasAttribute('weight_cost', $carrier_resource);
        $this->assertInstanceOf("Kazoo\\Api\\Resources\\CarrierResource", $carrier_resource);
    }

}