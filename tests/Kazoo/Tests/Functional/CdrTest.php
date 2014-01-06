<?php

namespace Kazoo\Tests\Functional;


/**
 * @group functional
 */
class CdrTest extends \PHPUnit_Framework_TestCase {
    
    public function setUp() {

        $username = 'bwann';
        $password = '12341234';
        $sipRealm = 'sip.benwann.com';
        $options = array();
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
