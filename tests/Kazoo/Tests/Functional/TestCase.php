<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Client;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class TestCase extends \PHPUnit_Framework_TestCase {

    protected $client;

    public function setUp() {

        $username = 'testuser';
        $password = 'a12341234';
        $sipRealm = 'sip.test.com';
        $token = null;

        // You have to specify authentication here to run full suite

        try {
            $this->client = new Client($username, $password, $sipRealm, $token, array("base_url" => "http://192.168.56.111:8000"));
        } catch (ApiLimitExceedException $e) {
            $this->markTestSkipped('API limit reached. Skipping to prevent unnecessary failure.');
        } catch (RuntimeException $e) {
            if ('Requires authentication' == $e->getMessage()) {
                $this->markTestSkipped('Test requires authentication. Skipping to prevent unnecessary failure.');
            }
        }
    }

}
