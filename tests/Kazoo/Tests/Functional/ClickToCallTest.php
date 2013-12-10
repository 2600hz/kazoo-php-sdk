<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Resources\ClickToCall;

/**
 * @group functional
 */
class ClickTest extends TestCase {

    /**
     * @test
     */
    public function testEmptyShell() {
        $click_to_call= $this->client->api('accounts')->click_to_calls()->new();
        $this->assertObjectHasAttribute('name', $click_to_call);
        $this->assertObjectHasAttribute('extension', $click_to_call);
        $this->assertObjectHasAttribute('realm', $click_to_call);
        $this->assertObjectHasAttribute('auth_required', $click_to_call);
        $this->assertObjectHasAttribute('whitelist', $click_to_call);
        $this->assertObjectHasAttribute('throttle', $click_to_call);
        $this->assertInstanceOf("Kazoo\\Api\\Resources\\ClickToCall", $click_to_call);
    }

}
