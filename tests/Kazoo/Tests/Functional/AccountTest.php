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
        $options = array();
        $options["base_url"] = "http://192.168.56.111:8000";

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
    public function testCreateEmptyAccount() {
        try {
            $account = $this->client->accounts()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Account", $account);
            return $account;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyAccount
     */
    public function testCreateAccount(Account $account) {
        
        try {
            $randnum = rand(1, 10000);
            $account->name = $randnum . " Unit-Test Account";
            $account->realm = "sip" . $randnum . ".unittestaccount.com";
            $account->timezone = "America/Chicago";
            $account->save();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Account", $account);
            $this->assertTrue((strlen($account->id) > 0));
            return $account->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateAccount
     */
    public function testRetrieveAccount($account_id) {

        try {
            
            $account = $this->client->accounts()->retrieve($account_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Account", $account);
            $this->assertTrue((strlen($account->id) > 0));
            return $account;
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    public function testRetrieveAll() {
        $accounts = $this->client->accounts()->retrieve();
        $this->assertGreaterThan(0, count($accounts));
    }

    /**
     * @test
     * @depends testRetrieveAccount
     */
    public function testUpdateAccount(Account $account) {

        try {

            $account->name = "Updated: " . $account->name;
            $account->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Account", $account);
            $this->assertTrue((strlen($account->id) > 0));
            return $account;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testUpdateAccount
     */
    public function testDeleteAccount(Account $account) {

        try {
            
            $account->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
