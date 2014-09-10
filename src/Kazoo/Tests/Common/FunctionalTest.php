<?php

namespace Kazoo\Tests\Common;

use \PHPUnit_Framework_TestCase;

use \Kazoo\SDK;

use \Kazoo\AuthToken\ApiKey;
use \Kazoo\AuthToken\User;
use \Kazoo\AuthToken\Exception\Unauthenticated;
use \Kazoo\Api\Exception\RateLimit;

class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    private $sdk;

    public function setUp() {
        @session_start();

        if(empty($GLOBALS['api_key'])){
            $auth_token = new User($GLOBALS['auth_username'], $GLOBALS['auth_password'], $GLOBALS['auth_realm']);
        } else {
            $auth_token = new ApiKey($GLOBALS['api_key']);
        }
        
        if(empty($GLOBALS['base_url'])){
            $options = array();
        } else {
            $options = array('base_url' => $GLOBALS['base_url']);
        }

        try {
            $this->sdk = new SDK($auth_token, $options);
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
