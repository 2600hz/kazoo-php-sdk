<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Client;

/**
 * @group functional
 */
class ClientTest extends TestCase {

    /**
     * @test
     */
    public function testClientAuth() {
        $auth_details = $this->client->getClientState();
        $this->assertObjectHasAttribute('auth_token', $auth_details);
        $this->assertObjectHasAttribute('status', $auth_details);
        $this->assertObjectHasAttribute('request_id', $auth_details);
        $this->assertObjectHasAttribute('revision', $auth_details);
        $this->assertObjectHasAttribute('data', $auth_details);
        $this->assertObjectHasAttribute('account_id', $auth_details->data);
        $this->assertObjectHasAttribute('owner_id', $auth_details->data);
        $this->assertObjectHasAttribute('is_reseller', $auth_details->data);
        $this->assertObjectHasAttribute('reseller_id', $auth_details->data);
        $this->assertObjectHasAttribute('apps', $auth_details->data);
        $this->assertObjectHasAttribute('language', $auth_details->data);
    }
}
