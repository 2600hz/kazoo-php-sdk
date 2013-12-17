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
}
