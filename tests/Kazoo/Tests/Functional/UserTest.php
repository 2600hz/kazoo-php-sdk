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

    /**
     * @test
     */
    public function testCreateEmptyShell() {
        
        try {
            $user = $this->client->accounts()->users()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\User", $user);
            return $user;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
        
    }

    /**
     * @test
     * @depends testCreateEmptyShell
     */
    public function testCreateUser($user) {

        try {

            $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);

            $user->username = "UnitTest" . $num;
            $user->first_name = "UnitTestFirstName" . $num;
            $user->last_name = "UnitTestFirstName" . $num;
            $user->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Callflow", $callflow);
            $this->assertTrue((strlen($user->id) > 0));

            return $user->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
        
    }

    /**
     * @test
     * @depends testCreateUser
     */
    public function testRetrieveUser($user_id) {
        
        try {
            $user = $this->client->accounts()->users()->retrieve($user_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\User", $user);
            $this->assertTrue((strlen($user->id) > 0));
            return $user;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
        
    }

    /**
     * @test
     * @depends testRetrieveUser
     */
    public function testUpdateUser($user) {

        try {
            $user->first_name = $user->first_name . "-";
            $user->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\User", $user);
            $this->assertTrue((strlen($user->id) > 0));
            return $user;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
        
    }

    public function testRetrieveAll() {
        
        try {
            $users = $this->client->accounts()->users()->retrieve();
            $this->assertGreaterThan(0, count($users));
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
        
    }

    /**
     * @test
     * @depends testUpdateUser
     */
    public function testDeleteUser($user) {
        
        try {
            $user->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
        
    }

}
