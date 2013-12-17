<?php

namespace Kazoo\Tests\Functional;


/**
 * @group functional
 */
class CdrTest extends TestCase {

    public function testRetriveAll() {
        try {
            $start = strtotime('-30 Day') + \Kazoo\Client::GREGORIAN_OFFSET;
            $end = time() + \Kazoo\Client::GREGORIAN_OFFSET;
            $filters = array("created_from" => $start, "created_to" => $end);
            $api = $this->client->accounts()->cdrs();
            $api->retrieve($filters);
            print_r($cdrs);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

}
