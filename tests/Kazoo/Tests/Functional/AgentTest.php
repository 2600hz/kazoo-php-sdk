<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Agent;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class AgentTest extends \PHPUnit_Framework_TestCase {

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
    public function testCreateEmptyAgent() {

        try {
            $agent = $this->client->accounts()->agents()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Agent", $agent);

            return $agent;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyAgent
     */
    public function testCreateAgent($agent) {

        try {
            $num = rand(1, 10000);

            $agent->name = "Test Agent #" . $num;
            $agent->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Agent", $agent);
            $this->assertTrue((strlen($agent->id) > 0));

            return $agent->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateAgent
     */
    public function testRetrieveAgent($agent_id) {

        try {
            $agent = $this->client->accounts()->agents()->retrieve($agent_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Agent", $agent);
            $this->assertTrue((strlen($agent->id) > 0));
            return $agent;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveAgent
     */
    public function testUpdateAgent($agent) {

        try {
            $agent->name = "Updated: " . $agent->name;
            $agent->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Agent", $agent);
            $this->assertTrue((strlen($agent->id) > 0));

            return $agent;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testUpdateAgent
     */
    public function testRetrieveAllAndUpdateOne($search_agent) {
        
        try {
            
            $agents = $this->client->accounts()->agents()->retrieve();
            foreach($agents as $agent){
                if($agent->id == $search_agent->id){
                    $search_agent->name = "Updated: " . $search_agent->name;
                    $search_agent->save();
                }
            }
            $this->assertGreaterThan(0, count($agents));
            return $search_agent;
            
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
    public function testDeleteAgent($agent) {

        try {
            $agent->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
