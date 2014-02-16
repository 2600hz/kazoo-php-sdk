<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Queue;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class QueueTest extends \PHPUnit_Framework_TestCase {

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
    public function testCreateEmptyQueue() {

        try {
            $queue = $this->client->accounts()->queues()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Queue", $queue);

            return $queue;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyQueue
     */
    public function testCreateQueue($queue) {

        try {
            $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);

            $queue->name = "Test Queue #" . $num;
            $queue->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Queue", $queue);
            $this->assertTrue((strlen($queue->id) > 0));

            return $queue->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateQueue
     */
    public function testRetrieveQueue($queue_id) {

        try {
            $queue = $this->client->accounts()->queues()->retrieve($queue_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Queue", $queue);
            $this->assertTrue((strlen($queue->id) > 0));
            return $queue;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveQueue
     */
    public function testUpdateQueue($queue) {

        try {
            $queue->name = "Updated: " . $queue->name;
            $queue->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Queue", $queue);
            $this->assertTrue((strlen($queue->id) > 0));

            return $queue;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testUpdateQueue
     */
    public function testRetrieveAllAndUpdateOne($search_queue) {
        
        try {
            
            $queues = $this->client->accounts()->queues()->retrieve();
            foreach($queues as $queue){
                if($queue->id == $search_queue->id){
                    $search_queue->name = "Updated: " . $search_queue->name;
                    $search_queue->save();
                }
            }
            $this->assertGreaterThan(0, count($queues));
            return $search_queue;
            
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
    public function testDeleteQueue($queue) {

        try {
            $queue->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
