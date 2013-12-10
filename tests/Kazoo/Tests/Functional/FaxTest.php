<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Resources\Fax;

/**
 * @group functional
 */
class FaxTest extends TestCase {

    /**
     * @test
     */
    public function testEmptyShell() {
        $fax = $this->client->api('accounts')->faxes()->new();
        $this->assertObjectHasAttribute('document', $fax);
        $this->assertObjectHasAttribute('notifications', $fax);
        $this->assertObjectHasAttribute('tx_result', $fax);
        $this->assertObjectHasAttribute('callback', $fax);
        $this->assertInstanceOf("Kazoo\\Api\\Resources\\Fax", $fax);
    }

}