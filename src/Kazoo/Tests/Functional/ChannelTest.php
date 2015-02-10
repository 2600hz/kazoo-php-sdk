<?php

/**
 * Requires:
 *   Kazoo 3.19+
 *     sup crossbar_maintenance start_module cb_channels // enable the API endpont
 */

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class ChannelTest extends FunctionalTest
{
    // don't run tests against the primary account, it will delete system channels
    private $account_id = '0a936fc79bdb4a8c38e6089ab44ad030';

    /**
     * @test
     */
    public function testListingChannels() {
        $channels = $this->getSDK()->Account()->Channels();

        $channel = null;
        foreach($channels as $element) {
            if ($element->id == $channel_id) {
                $channel = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Channel", $channel);
        $this->assertTrue((strlen($channel->getId()) > 0));
        $this->assertEquals($channel->getId(), $channel_id);

        return $channel->friendly_name;
    }

    /**
     * @test
     * @depends testId
     */
    public function testFetchChannel($channel_id) {
        $channel = $this->getSDK()->Account($this->account_id)->Channel($channel_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Channel", $channel);
        $this->assertTrue((strlen($channel->getId()) > 0));
        $this->assertEquals($channel->getId(), $channel_id);

        return $channel;
    }

    /**
     * @test
     * @depends testFetchChannel
     */
    public function testHangupChannel($channel) {
        $data = new stdClass();
        $data->action = "hangup";

        $channel->execute($data);
    }

    /**
     * @test
     * @depends testListingChannels
     */
    public function testFilteredListingChannels($channel_id) {
        $filter = array('filter_direction' => "inbound");
        $channels = $this->getSDK()->Account($this->account_id)->Channels($filter);

        $this->assertTrue(count($channels) == 1);

        $element = $channels->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $channel_id);

        $filter = array('filter_direction' => "up");
        $channels = $this->getSDK()->Account($this->account_id)->Channels($filter);

        $this->assertTrue(count($channels) == 0);
    }
}
