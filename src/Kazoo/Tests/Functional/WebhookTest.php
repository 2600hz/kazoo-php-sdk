<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class WebhookTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateWebhook() {
        $webhook = $this->getSDK()->Account()->Webhook();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Webhook", $webhook);
        $this->assertTrue((strlen($webhook->getId()) == 0));

        $webhook->name = "SDK Create Test " . rand(100, 1000);
        $webhook->uri = "http://my.server.com/test.php";
        $webhook->hook = "channel_create";
        $webhook->http_verb = "post";
        $webhook->save();

        $this->assertTrue((strlen($webhook->getId()) > 0));
        return $webhook->getId();
    }

    /**
     * @test
     * @depends testCreateWebhook
     */
    public function testFetchWebhook($webhook_id) {
        $webhook = $this->getSDK()->Account()->Webhook($webhook_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Webhook", $webhook);
        $this->assertTrue((strlen($webhook->getId()) > 0));
        $this->assertEquals($webhook->getId(), $webhook_id);

        return $webhook;
    }

    /**
     * @test
     * @depends testFetchWebhook
     */
    public function testUpdateWebhook($webhook) {
        $webhook_id = $webhook->getId();
        $current_name = $webhook->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $webhook->name = $new_name;
        $webhook->save();

        // Make sure we didn't create a new webhook
        $this->assertEquals($webhook->getId(), $webhook_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($webhook->name, $new_name);

        // Fetch it from the database again to make sure
        $webhook->fetch();
        $this->assertEquals($webhook->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateWebhook
     */
    public function testListingWebhooks($webhook_id) {
        $webhooks = $this->getSDK()->Account()->Webhooks();

        $webhook = null;
        foreach($webhooks as $element) {
            if ($element->id == $webhook_id) {
                $webhook = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Webhook", $webhook);
        $this->assertTrue((strlen($webhook->getId()) > 0));
        $this->assertEquals($webhook->getId(), $webhook_id);

        return $webhook->name;
    }

    /**
     * @test
     * @depends testCreateWebhook
     * @depends testListingWebhooks
     */
    public function testFilteredListingWebhooks($webhook_id, $webhook_name) {
        $filter = array('filter_name' => $webhook_name);
        $webhooks = $this->getSDK()->Account()->Webhooks($filter);

        $this->assertTrue(count($webhooks) == 1);

        $element = $webhooks->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $webhook_id);

        $filter = array('filter_name' => 'no-such-webhook');
        $webhooks = $this->getSDK()->Account()->Webhooks($filter);

        $this->assertTrue(count($webhooks) == 0);
    }

    /**
     * @test
     * @depends testFetchWebhook
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildWebhook($webhook) {
        $webhook_id = $webhook->getId();

        $this->assertTrue((strlen($webhook->getId()) > 0));

        $webhook->remove();

        $this->assertTrue((strlen($webhook->getId()) == 0));

//        $webhook($webhook_id)->fetch();
    }

}
