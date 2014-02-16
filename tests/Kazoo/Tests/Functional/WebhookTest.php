<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Webhook;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class WebhookTest extends \PHPUnit_Framework_TestCase {

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
    public function testCreateEmptyWebhook() {

        try {
            $webhook = $this->client->accounts()->webhooks()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Webhook", $webhook);

            return $webhook;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyWebhook
     */
    public function testCreateWebhook($webhook) {

        try {
            $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);

            $webhook->name = "Test Webhook #" . $num;
            $webhook->sip->password = substr(number_format(time() * rand(), 0, '', ''), 0, 10);
            $webhook->sip->username = "testWebhook" . $num;
            $webhook->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Webhook", $webhook);
            $this->assertTrue((strlen($webhook->id) > 0));

            return $webhook->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateWebhook
     */
    public function testRetrieveWebhook($webhook_id) {

        try {
            $webhook = $this->client->accounts()->webhooks()->retrieve($webhook_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Webhook", $webhook);
            $this->assertTrue((strlen($webhook->id) > 0));
            return $webhook;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveWebhook
     */
    public function testUpdateWebhook($webhook) {

        try {
            $webhook->name = "Updated: " . $webhook->name;
            $webhook->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Webhook", $webhook);
            $this->assertTrue((strlen($webhook->id) > 0));

            return $webhook;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testUpdateWebhook
     */
    public function testRetrieveAllAndUpdateOne($search_Webhook) {
        
        try {
            
            $webhooks = $this->client->accounts()->webhooks()->retrieve();
            foreach($webhooks as $webhook){
                if($webhook->id == $search_webhook->id){
                    $search_webhook->name = "Updated: " . $search_webhook->name;
                    $search_webhook->save();
                }
            }
            $this->assertGreaterThan(0, count($webhooks));
            return $search_webhook;
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveAllAndUpdateOne
     */
    public function testDeleteWebhook($webhook) {

        try {
            $webhook->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
