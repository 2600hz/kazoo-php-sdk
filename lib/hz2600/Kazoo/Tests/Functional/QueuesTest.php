<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class QueueTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateQueue() {
        $queue = $this->getSDK()->Account()->Queue();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Queue", $queue);
        $this->assertTrue((strlen($queue->getId()) == 0));

        $queue->name = "SDK Create Test " . rand(100, 1000);
        $queue->save();

        $this->assertTrue((strlen($queue->getId()) > 0));
        return $queue->getId();
    }

    /**
     * @test
     * @depends testCreateQueue
     */
    public function testFetchQueue($queue_id) {
        $queue = $this->getSDK()->Account()->Queue($queue_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Queue", $queue);
        $this->assertTrue((strlen($queue->getId()) > 0));
        $this->assertEquals($queue->getId(), $queue_id);

        return $queue;
    }

    /**
     * @test
     * @depends testFetchQueue
     */
    public function testUpdateQueue($queue) {
        $queue_id = $queue->getId();
        $current_name = $queue->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $queue->name = $new_name;
        $queue->save();

        // Make sure we didn't create a new queue
        $this->assertEquals($queue->getId(), $queue_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($queue->name, $new_name);

        // Fetch it from the database again to make sure
        $queue->fetch();
        $this->assertEquals($queue->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateQueue
     */
    public function testListingQueues($queue_id) {
        $queues = $this->getSDK()->Account()->Queues();

        $queue = null;
        foreach($queues as $element) {
            if ($element->id == $queue_id) {
                $queue = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Queue", $queue);
        $this->assertTrue((strlen($queue->getId()) > 0));
        $this->assertEquals($queue->getId(), $queue_id);

        return $queue->name;
    }

    /**
     * @test
     * @depends testCreateQueue
     * @depends testListingQueues
     */
    public function testFilteredListingQueues($queue_id, $queue_name) {
        $filter = array('filter_name' => $queue_name);
        $queues = $this->getSDK()->Account()->Queues($filter);

        $this->assertTrue(count($queues) == 1);

        $element = $queues->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $queue_id);

        $filter = array('filter_name' => 'no-such-queue');
        $queues = $this->getSDK()->Account()->Queues($filter);

        $this->assertTrue(count($queues) == 0);
    }

    /**
     * @test
     * @depends testFetchQueue
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildQueue($queue) {
        $queue_id = $queue->getId();

        $this->assertTrue((strlen($queue->getId()) > 0));

        $queue->remove();

        $this->assertTrue((strlen($queue->getId()) == 0));

//        $queue($queue_id)->fetch();
    }

}
