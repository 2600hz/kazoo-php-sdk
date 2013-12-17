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
            $cdrs = $this->client->accounts()->cdrs()->retrieve($filters);
            $this->assertGreaterThan(0, count($cdrs->data));
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

}
