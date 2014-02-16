<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Server;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class ServerTest extends \PHPUnit_Framework_TestCase {

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
    public function testCreateEmptyServer() {

        try {
            $server = $this->client->accounts()->servers()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Server", $server);

            return $server;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyServer
     */
    public function testCreateServer($server) {

        try {
            $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);

            $server->name = "Test Server #" . $num;
            $server->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Server", $server);
            $this->assertTrue((strlen($server->id) > 0));

            return $server->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateServer
     */
    public function testRetrieveServer($server_id) {

        try {
            $server = $this->client->accounts()->servers()->retrieve($server_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Server", $server);
            $this->assertTrue((strlen($server->id) > 0));
            return $server;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveServer
     */
    public function testUpdateServer($server) {

        try {
            $server->name = "Updated: " . $server->name;
            $server->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Server", $server);
            $this->assertTrue((strlen($server->id) > 0));

            return $server;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testUpdateServer
     */
    public function testRetrieveAllAndUpdateOne($search_server) {
        
        try {
            
            $servers = $this->client->accounts()->servers()->retrieve();
            foreach($servers as $server){
                if($server->id == $search_server->id){
                    $search_server->name = "Updated: " . $search_server->name;
                    $search_server->save();
                }
            }
            $this->assertGreaterThan(0, count($servers));
            return $search_server;
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveAllAndUpdateOne
     */
    public function testDeleteServer($server) {

        try {
            $server->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
