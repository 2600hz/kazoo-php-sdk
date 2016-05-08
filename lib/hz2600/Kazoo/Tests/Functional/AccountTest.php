<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class AccountTest extends FunctionalTest
{
    private $test_object = '{
        "company_name":"Test11",
        "name": "NameTest11",
        "nav":{
            "help":"",
            "learn_more":""},
            "hide_powered":true,
            "hide_registration":false,
            "hide_credits":false,
            "domain":"test.11.test",
            "fake_api_url":"",
            "port":{"loa":"","resporg":"","support_email":"","features":"","terms":""},
            "inbound_trunks_price":"",
            "twoway_trunks_price":""
    }';

    /**
     * @test
     */
    public function testGetMyAccount() {
        $account = $this->getSDK()->Account();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Account", $account);
        $this->assertTrue((strlen($account->getId()) > 0));
    }

    /**
     * @test
     */
    public function testUpdateMyAccount() {
        $account = $this->getSDK()->Account();
        $account_id = $account->getId();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Account", $account);
        $this->assertTrue((strlen($account_id) > 0));

        $current_name = $account->name;
        $new_name = "SDK Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $account->name = $new_name;
        $account->save();

        // Make sure we didn't create a new account
        $this->assertTrue($account->getId() == $account_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($account->name, $new_name);

        // Fetch it from the database again to make sure
        $account->fetch();
        $this->assertEquals($account->name, $new_name);

        $account->name = $current_name;
        $account->save();
    }

    /**
     * @test
     */
    public function testCreateChildAccount() {
        $account = $this->getSDK()->Account(null);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Account", $account);
        $this->assertTrue((strlen($account->getId()) == 0));

        $account->name = "SDK Create Test " . rand(100, 1000);
        $account->save();

        $this->assertTrue((strlen($account->getId()) > 0));

        return $account->getId();
    }

    /**
     * @test
     * @depends testCreateChildAccount
     */
    public function testUpdateChildAccount($account_id) {
        $account = $this->getSDK()->Account($account_id);

        $this->assertTrue((strlen($account->getId()) > 0));
        $this->assertEquals($account->getId(), $account_id);

        $current_name = $account->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $account->name = $new_name;
        $account->save();

        // Make sure we didn't create a new account
        $this->assertEquals($account->getId(), $account_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($account->name, $new_name);

        // Fetch it from the database again to make sure
        $account->fetch();
        $this->assertEquals($account->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateChildAccount
     */
    public function testImage($account_id) {
        $account = $this->getSDK()->Account($account_id);
        $this->assertEquals($account->getId(), $account_id);
        
        //TODO: Requires a base64 image
        //$account->image('','logo');
    }

    /**
     * @test
     * @depends testCreateChildAccount
     */
    public function testCreateWhitelabel($account_id) {
        $account = $this->getSDK()->Account($account_id);
        $this->assertEquals($account->getId(), $account_id);
        
        $this->assertTrue(is_object($account->whitelabelCreate(json_decode($this->test_object))));
    }

    /**
     * @test
     * @depends testCreateChildAccount
     */
    public function testWhitelabel($account_id) {
        $account = $this->getSDK()->Account($account_id);
        $this->assertEquals($account->getId(), $account_id);

        $this->assertTrue(is_object($account->whitelabel()));
    }

    /**
     * @test
     * @depends testCreateChildAccount
     */
    public function testUpdateWhitelabel($account_id) {
        $account = $this->getSDK()->Account($account_id);
        $this->assertEquals($account->getId(), $account_id);

        $this->assertTrue(is_object($account->whitelabelUpdate(json_decode($this->test_object))));
    }

    /**
     * @test
     * @depends testCreateChildAccount
     */
    public function testRemoveChildAccount($account_id) {
        $account = $this->getSDK()->Account($account_id);

        $this->assertTrue((strlen($account->getId()) > 0));
        $this->assertEquals($account->getId(), $account_id);

        $account->remove();

        $this->assertTrue((strlen($account->getId()) == 0));
    }
}