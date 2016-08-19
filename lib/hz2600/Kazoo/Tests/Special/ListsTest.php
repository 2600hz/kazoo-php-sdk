<?php

namespace Kazoo\Tests\Special;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class ListTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateList() {
        $list = $this->getSDK()->Account()->MyList();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\MyList", $list);
        $this->assertTrue((strlen($list->getId()) == 0));

        $list->name = "SDK Create Test " . rand(100, 1000);
        $list->save();

        $this->assertTrue((strlen($list->getId()) > 0));
        return $list->getId();
    }

    /**
     * @test
     * @depends testCreateList
     */
    public function testFetchList($list_id) {
        $list = $this->getSDK()->Account()->MyList($list_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\MyList", $list);
        $this->assertTrue((strlen($list->getId()) > 0));
        $this->assertEquals($list->getId(), $list_id);

        return $list;
    }

    /**
     * @test
     * @depends testFetchList
     */
    public function testUpdateList($list) {
        $list_id = $list->getId();
        $current_name = $list->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $list->name = $new_name;
        $list->save();

        // Make sure we didn't create a new list
        $this->assertEquals($list->getId(), $list_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($list->name, $new_name);

        // Fetch it from the database again to make sure
        $list->fetch();
        $this->assertEquals($list->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateList
     */
    public function testListingLists($list_id) {
        $lists = $this->getSDK()->Account()->MyLists();

        $list = null;
        foreach($lists as $element) {
            if ($element->id == $list_id) {
                $list = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\MyList", $list);
        $this->assertTrue((strlen($list->getId()) > 0));
        $this->assertEquals($list->getId(), $list_id);

        return $list->name;
    }

    /**
     * @test
     * @depends testCreateList
     * @depends testListingLists
     */
    public function testFilteredListingLists($list_id, $list_name) {
        $filter = array('filter_name' => $list_name);
        $lists = $this->getSDK()->Account()->MyLists($filter);

        $this->assertTrue(count($lists) == 1);

        $element = $lists->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $list_id);

        $filter = array('filter_name' => 'no-such-list');
        $lists = $this->getSDK()->Account()->MyLists($filter);

        $this->assertTrue(count($lists) == 0);
    }

    /**
     * @test
     * @depends testFetchList
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildList($list) {
        $list_id = $list->getId();

        $this->assertTrue((strlen($list->getId()) > 0));

        $list->remove();

        $this->assertTrue((strlen($list->getId()) == 0));

//        $list($list_id)->fetch();
    }

}
