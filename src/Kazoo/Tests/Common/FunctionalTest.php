<?php

namespace Kazoo\Tests\Common;

use \PHPUnit_Framework_TestCase;

use \Kazoo\SDK;

use \Kazoo\AuthToken\ApiKey;
use \Kazoo\AuthToken\Exception\Unauthenticated;

use \Kazoo\Api\Exception\RateLimit;

class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    private $sdk;

    public function setUp() {
        @session_start();
        $api_key = $GLOBALS['api_key'];
        $auth_token = new ApiKey($api_key);

        try {
            $this->sdk = new SDK($auth_token);
        } catch (RateLimit $e) {
            $this->markTestSkipped('API limit reached. Skipping to prevent unnecessary failure.');
        } catch (Unauthenticated $e) {
            $this->markTestSkipped('Test requires authentication. Skipping to prevent unnecessary failure.');
        }
    }

    public function getSDK() {
        return $this->sdk;
    }
}
