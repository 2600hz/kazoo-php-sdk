<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Resources\Account;

/**
 * @group functional
 */
class AccountTest extends TestCase {

    /**
     * @test
     */
    public function testEmptyShell() {
        $account = $this->client->api('accounts')->new();
        $this->assertObjectHasAttribute('name', $account);
        $this->assertObjectHasAttribute('realm', $account);
        $this->assertObjectHasAttribute('timezone', $account);
        $this->assertObjectHasAttribute('caller_id', $account);
        $this->assertInstanceOf("Kazoo\\Api\\Resources\\Account", $account);
    }
    
    public function testGetAccounts() {
        $accounts = $this->client->api('accounts')->get();
        print_r($accounts);
        die();
    }
}
