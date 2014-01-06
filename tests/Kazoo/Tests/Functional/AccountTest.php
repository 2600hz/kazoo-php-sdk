<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Account;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class AccountTest extends \PHPUnit_Framework_TestCase {

    protected $client;

    public function setUp() {

        $username = 'bwann';
        $password = '12341234';
        $sipRealm = 'sip.benwann.com';
        $options  = array();
        $options["base_url"] = "http://192.168.0.111:8000";
        $options["log_type"] = "file";
        $options["log_file"] = "/var/log/kazoo-sdk.log";

        // You have to specify authentication here to run full suite

        try {
            $this->client = new \Kazoo\Client($username, $password, $sipRealm, $options);
        } catch (ApiLimitExceedException $e) {
            $this->markTestSkipped('API limit reached. Skipping to prevent unnecessary failure.');
        } catch (RuntimeException $e) {
            if ('Requires authentication' == $e->getMessage()) {
                $this->markTestSkipped('Test requires authentication. Skipping to prevent unnecessary failure.');
            }
        }
    }
    
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

    public function testRetriveOne() {
        $account = $this->client->accounts()->retrieve($this->client->getAccountContext());
        $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Account", $account);
    }
    
    public function testUpdateOne() {
        $account = $this->client->retrieve($this->client->getAccountContext());
        $account->name = "Updated name";
        $account->save();
        $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Account", $account);
    }

    public function testRetriveAll() {
        $accounts = $this->client->accounts()->retrieve();
        $this->assertGreaterThan(0, count($accounts->data));
    }

    public function testCreateAccount() {
        $account = $this->client->accounts()->new();
        $account->name = "New Test Account";
        $account->realm = "sip".rand(0,10000).".testaccount.com";
        $account->timezone = "America/Chicago";
        $this->client->accounts()->create($account);
        $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Account", $account);
        $this->assertObjectHasAttribute('id', $account);
    }

}
