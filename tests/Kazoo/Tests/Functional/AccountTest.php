<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Account;

/**
 * @group functional
 */
class AccountTest extends TestCase {

    /**
     * @test
     */
    public function testEmptyShell() {
        try {
            $account = $this->client->accounts()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Account", $account);
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    public function testRetriveAll() {
        $accounts = $this->client->accounts()->retrieve();
        $this->assertGreaterThan(0, count($accounts->data));
    }

    public function testCreateAccount() {
        $shellAccount = $this->client->accounts()->new();
        $shellAccount->name = "New Test Account";
        $shellAccount->realm = "sip".rand(0,10000).".testaccount.com";
        $shellAccount->timezone = "America/Chicago";
        $newAccount = $this->client->accounts()->create($shellAccount);
        $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Account", $newAccount);
        $this->assertObjectHasAttribute('id', $newAccount);
    }

}
