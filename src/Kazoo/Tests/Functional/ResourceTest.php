<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class ResourceTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateResource() {
        $resource = $this->getSDK()->Account()->Resource();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Resource", $resource);
        $this->assertTrue((strlen($resource->getId()) == 0));

        $resource->name = "SDK Create Test ";
        $resource->gateways = array();
        $resource->save();

        $this->assertTrue((strlen($resource->getId()) > 0));
        return $resource->getId();
    }

    /**
     * @test
     * @depends testCreateResource
     */
    public function testFetchResource($resource_id) {
        $resource = $this->getSDK()->Account()->Resource($resource_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Resource", $resource);
        $this->assertTrue((strlen($resource->getId()) > 0));
        $this->assertEquals($resource->getId(), $resource_id);

        return $resource;
    }

    /**
     * @test
     * @depends testFetchResource
     */
    public function testUpdateResource($resource) {
        $resource_id = $resource->getId();
        $current_name = $resource->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $resource->name = $new_name;
        $resource->save();

        // Make sure we didn't create a new resource
        $this->assertEquals($resource->getId(), $resource_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($resource->name, $new_name);

        // Fetch it from the database again to make sure
        $resource->fetch();
        $this->assertEquals($resource->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateResource
     */
    public function testListingResources($resource_id) {
        $resources = $this->getSDK()->Account()->Resources();

        $resource = null;
        foreach($resources as $element) {
            if ($element->id == $resource_id) {
                $resource = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Resource", $resource);
        $this->assertTrue((strlen($resource->getId()) > 0));
        $this->assertEquals($resource->getId(), $resource_id);

        return $resource->name;
    }

    /**
     * @test
     * @depends testCreateResource
     * @depends testListingResources
     */
    public function testFilteredListingResources($resource_id, $resource_name) {
        $filter = array('filter_name' => $resource_name);
        $resources = $this->getSDK()->Account()->Resources($filter);

        $this->assertTrue(count($resources) == 1);

        $element = $resources->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $resource_id);

        $filter = array('filter_name' => 'no-such-resource');
        $resources = $this->getSDK()->Account()->Resources($filter);

        $this->assertTrue(count($resources) == 0);
    }

    /**
     * @test
     * @depends testFetchResource
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildResource($resource) {
        $resource_id = $resource->getId();

        $this->assertTrue((strlen($resource->getId()) > 0));

        $resource->remove();

        $this->assertTrue((strlen($resource->getId()) == 0));

//        $resource($resource_id)->fetch();
    }

}