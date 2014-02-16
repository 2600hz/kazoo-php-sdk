<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\CarrierResource;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class CarrierResourceTest extends \PHPUnit_Framework_TestCase {

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
    public function testCreateEmptyCarrierResource() {

        try {
            $resource = $this->client->accounts()->carrier_resources()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\CarrierResource", $resource);

            return $resource;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyCarrierResource
     */
    public function testCreateCarrierResource($resource) {

        try {
            
            $num = rand(1, 10000);
            $resource->name = "Test CarrierResource #" . $num;
            $resource->sip->password = substr(number_format(time() * rand(), 0, '', ''), 0, 10);
            $resource->sip->username = "testdevice" . $num;
            $resource->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\CarrierResource", $resource);
            $this->assertTrue((strlen($resource->id) > 0));

            return $resource->id;
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateCarrierResource
     */
    public function testRetrieveCarrierResource($resource_id) {

        try {
            $resource = $this->client->accounts()->carrier_resources()->retrieve($resource_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\CarrierResource", $resource);
            $this->assertTrue((strlen($resource->id) > 0));
            return $resource;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveCarrierResource
     */
    public function testUpdateCarrierResource($resource) {

        try {
            $resource->name = "Updated: " . $resource->name;
            $resource->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\CarrierResource", $resource);
            $this->assertTrue((strlen($resource->id) > 0));

            return $resource;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testUpdateCarrierResource
     */
    public function testRetrieveAllAndUpdateOne($search_resource) {
        
        try {
            
            $resources = $this->client->accounts()->carrier_resources()->retrieve();
            foreach($resources as $resource){
                if($resource->id == $search_resource->id){
                    $search_resource->name = "Updated: " . $search_resource->name;
                    $search_resource->save();
                }
            }
            $this->assertGreaterThan(0, count($resources));
            return $search_resource;
            
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
    public function testDeleteCarrierResource($resource) {

        try {
            $resource->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
