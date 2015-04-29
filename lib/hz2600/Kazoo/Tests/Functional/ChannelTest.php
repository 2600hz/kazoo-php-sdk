<?php

/**
 * Requires:
 *   Kazoo 3.19+
 *     sup crossbar_maintenance start_module cb_channels // enable the API endpont
 */

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;
use \stdClass; 

/**
 * @group functional
 */
class ChannelTest extends FunctionalTest
{
    // don't run tests against the primary account, it will delete system channels
   // private $account_id = '0a936fc79bdb4a8c38e6089ab44ad030';

    /**
     * @test
     */
    public function testListingChannels() {

        $this->markTestIncomplete(
            'This test requires live calls and does bad things to them.'
        );


        $channels = $this->getSDK()->Account()->Channels();

        $channel = null;
        foreach($channels as $element) {
            if (!empty($element)) {
                $channel = $element->fetch();
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Channel", $channel);
        $this->assertTrue((strlen($channel->getId()) > 0));

        return $channel->getId();
    }

    /**
     * @test
     * @depends testListingChannels
     */
    public function testFetchChannel($channel_id) {
        $channel = $this->getSDK()->Account()->Channel($channel_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Channel", $channel);
        $this->assertTrue((strlen($channel->getId()) > 0));
        $this->assertEquals($channel->getId(), $channel_id);

        return $channel;
    }

    /**
     * @test
     * @depends testFetchChannel
     */
    public function testTransferChannel($channel) {
        $data = new stdClass();
        $data->action = "transfer";

        $channel->executeCommand($data);
    }
}
