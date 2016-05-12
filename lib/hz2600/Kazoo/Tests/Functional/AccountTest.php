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

    private $base64_image = "data:image/png;base64,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";


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
     * @depends testWhitelabel
     */
    public function testImage($account_id) {
        $account = $this->getSDK()->Account($account_id);
        $this->assertEquals($account->getId(), $account_id);
        
        //TODO: There might be a problem with the way image() is implemented.
        //$account->image($base64_image, 'icon');

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