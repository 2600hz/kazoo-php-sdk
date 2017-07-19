<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

use \stdClass;
/**
 * @group functional
 */
class GroupTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateGroup() {
        $group = $this->getSDK()->Account()->Group();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Group", $group);
        $this->assertTrue((strlen($group->getId()) == 0));

        $group->name = "SDK Create Test " . rand(100, 1000);
        $group->endpoints = new stdClass;
        $group->save();

        $this->assertTrue((strlen($group->getId()) > 0));
        return $group->getId();
    }

    /**
     * @test
     * @depends testCreateGroup
     */
    public function testFetchGroup($group_id) {
        $group = $this->getSDK()->Account()->Group($group_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Group", $group);
        $this->assertTrue((strlen($group->getId()) > 0));
        $this->assertEquals($group->getId(), $group_id);

        return $group;
    }

    /**
     * @test
     * @depends testFetchGroup
     */
    public function testUpdateGroup($group) {
        $group_id = $group->getId();
        $current_name = $group->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $group->name = $new_name;
        $group->save();

        // Make sure we didn't create a new group
        $this->assertEquals($group->getId(), $group_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($group->name, $new_name);

        // Fetch it from the database again to make sure
        $group->fetch();
        $this->assertEquals($group->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateGroup
     */
    public function testListingGroups($group_id) {
        $groups = $this->getSDK()->Account()->Groups();
        
        $group = null;
        foreach($groups as $element) {
            if ($element->id == $group_id) {
                $group = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Group", $group);
        $this->assertTrue((strlen($group->getId()) > 0));
        $this->assertEquals($group->getId(), $group_id);

        return $group->name;
    }

    /**
     * @test
     * @depends testCreateGroup
     * @depends testListingGroups
     */
    public function testFilteredListingGroups($group_id, $group_name) {
        $filter = array('filter_name' => $group_name);
        $groups = $this->getSDK()->Account()->Groups($filter);

        $this->assertTrue(count($groups) == 1);

        $element = $groups->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $group_id);

        $filter = array('filter_name' => 'no-such-group');
        $groups = $this->getSDK()->Account()->groups($filter);

        $this->assertTrue(count($groups) == 0);
    }

    /**
     * @test
     * @depends testFetchGroup
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildGroup($group) {
        $group_id = $group->getId();

        $this->assertTrue((strlen($group->getId()) > 0));

        $group->remove();

        $this->assertTrue((strlen($group->getId()) == 0));

//        $group($group_id)->fetch();
    }

}
