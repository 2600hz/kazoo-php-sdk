<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class TestCase extends \PHPUnit_Framework_TestCase {

    protected $client;

    public function setUp() {

//        $username = 'testuser';
//        $password = 'a12341234';
//        $sipRealm = 'sip.test.com';
        $username = 'bwann';
        $password = '12341234';
        $sipRealm = 'sip.benwann.com';
        $options  = array();
        $options["base_url"] = "http://192.168.56.111:8000";
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

}
