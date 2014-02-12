<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\User;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class UserTest extends \PHPUnit_Framework_TestCase {

    protected $client;

    public function setUp() {

        $username = 'bwann';
        $password = '12341234';
        $sipRealm = 'sip.benwann.com';
        $options = array();
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

    public function testRetrieveAll() {
        $users = $this->client->accounts()->users()->retrieve();
        $this->assertGreaterThan(0, count($users));
    }

    /**
     * @test
     */
    public function testEmptyShell() {
        try {
            $user = $this->client->accounts()->users()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\User", $user);
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function testCreateUser() {
        
        $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
        
        $user = $this->client->accounts()->users()->new();
        $user->username = "UnitTest" . $num;
        $user->first_name = "UnitTestFirstName" . $num;
        $user->last_name = "UnitTestFirstName" . $num;
        $this->client->accounts()->users()->create($user);

        echo $user . "\n";
        die();

        $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\User", $user);
        $this->assertObjectHasAttribute('id', $user);

        return $user->id;
    }
//
//    /**
//     * @test
//     * @depends testCreateUser
//     */
//    public function testRetriveDevice($device_id) {
//        $device = $this->client->accounts()->devices()->retrieve($device_id);
//        $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Device", $device);
//        $this->assertObjectHasAttribute('id', $this->test_device);
//    }
//
//    /**
//     * @test
//     * @depends shouldCreateDevice
//     */
//    public function testUpdateOne($device_id) {
//        $device = $this->client->accounts()->devices()->retrieve($this->test_device->id);
//        $device->name = "Updated Name";
//        $device->save();
//
//        $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Device", $device);
//        $this->assertObjectHasAttribute('id', $device);
//    }
//
//    /**
//     * @test
//     * @depends shouldCreateDevice
//     */
//    public function testDeleteOne() {
//        $this->test_device = $this->client->accounts()->devices()->retrieve($this->test_device->id);
//        $this->test_device->delete();
//        $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
//    }

}
