<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\RingGroup;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class RingGroupTest extends \PHPUnit_Framework_TestCase {

    protected $client;

    public function setUp() {

        $username = 'bwann';
        $password = '12341234';
        $sipRealm = 'sip.benwann.com';
        $options = array();
        $options["base_url"] = "http://192.168.56.111:8000";
        $this->test_user_id = "985db99c5db1b23b6273183c18462616";
        $this->test_device_id = "0e234cdbb1bcd8087498a063eafbcd62";

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
    public function testCreateEmptyRingGroup() {

        try {
            
            $group = $this->client->accounts()->groups()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\RingGroup", $group);
            return $group;
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyRingGroup
     */
    public function testCreateRingGroup($ring_group) {

        try {
            $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);

            $ring_group->name = "Test Ring Group #" . $num;
            $ring_group->addUserToGroup($this->test_user_id);
            $ring_group->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\RingGroup", $ring_group);
            $this->assertTrue((strlen($ring_group->id) > 0));

            return $ring_group->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateRingGroup
     */
    public function testRetrieveRingGroup($ring_group_id) {

        try {
            
            $ring_group = $this->client->accounts()->groups()->retrieve($ring_group_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\RingGroup", $ring_group);
            $this->assertTrue((strlen($ring_group->id) > 0));
            return $ring_group;
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveRingGroup
     */
    public function testUpdateRingGroup($ring_group) {

        try {
            $ring_group->name = "Updated: " . $ring_group->name;
            $ring_group->addDeviceToGroup($this->test_device_id);
            $ring_group->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\RingGroup", $ring_group);
            $this->assertTrue((strlen($ring_group->id) > 0));

            return $ring_group;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testUpdateRingGroup
     */
    public function testRetrieveAllAndUpdateOne($search_ring_group) {
        
        try {
            
            $groups = $this->client->accounts()->groups()->retrieve();
            foreach($groups as $group){
                if($group->id == $search_ring_group->id){
                    $search_ring_group->name = "Updated: " . $search_ring_group->name;
                    $search_ring_group->save();
                }
            }
            $this->assertGreaterThan(0, count($groups));
            return $search_ring_group;
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

//    /**
//     * @test
//     * @depends testRetrieveAllAndUpdateOne
//     */
//    public function testDeleteRingGroup($ring_group) {
//
//        try {
//            $ring_group->delete();
//            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
//        } catch (RuntimeException $e) {
//            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
//        } catch (Exception $e) {
//            $this->markTestSkipped("Exception: " . $e->getMessage());
//        }
//    }

}
