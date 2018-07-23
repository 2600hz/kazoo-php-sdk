<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class BlacklistTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateBlacklist() {
        $blacklist = $this->getSDK()->Account()->Blacklist();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Blacklist", $blacklist);
        $this->assertTrue((strlen($blacklist->getId()) == 0));

        $blacklist->name = "SDK Create Test " . rand(100, 1000);
        $blacklist->save();

        $this->assertTrue((strlen($blacklist->getId()) > 0));
        return $blacklist->getId();
    }

    /**
     * @test
     * @depends testCreateBlacklist
     */
    public function testFetchBlacklist($blacklist_id) {
        $blacklist = $this->getSDK()->Account()->Blacklist($blacklist_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Blacklist", $blacklist);
        $this->assertTrue((strlen($blacklist->getId()) > 0));
        $this->assertEquals($blacklist->getId(), $blacklist_id);

        return $blacklist;
    }

    /**
     * @test
     * @depends testFetchBlacklist
     */
    public function testUpdateBlacklist($blacklist) {
        $blacklist_id = $blacklist->getId();
        $current_name = $blacklist->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $blacklist->name = $new_name;
        $blacklist->save();

        // Make sure we didn't create a new blacklist
        $this->assertEquals($blacklist->getId(), $blacklist_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($blacklist->name, $new_name);

        // Fetch it from the database again to make sure
        $blacklist->fetch();
        $this->assertEquals($blacklist->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateBlacklist
     */
    public function testListingBlacklists($blacklist_id) {
        $blacklists = $this->getSDK()->Account()->Blacklists();

        $blacklist = null;
        foreach($blacklists as $element) {
            if ($element->id == $blacklist_id) {
                $blacklist = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Blacklist", $blacklist);
        $this->assertTrue((strlen($blacklist->getId()) > 0));
        $this->assertEquals($blacklist->getId(), $blacklist_id);

        return $blacklist->name;
    }

    /**
     * @test
     * @depends testCreateBlacklist
     * @depends testListingBlacklists
     */
    public function testFilteredListingBlacklists($blacklist_id, $blacklist_name) {
        $filter = array('filter_name' => $blacklist_name);
        $blacklists = $this->getSDK()->Account()->Blacklists($filter);

        $this->assertTrue(count($blacklists) == 1);

        $element = $blacklists->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $blacklist_id);

        $filter = array('filter_name' => 'no-such-blacklist');
        $blacklists = $this->getSDK()->Account()->blacklists($filter);

        $this->assertTrue(count($blacklists) == 0);
    }

    /**
     * @test
     * @depends testFetchBlacklist
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildBlacklist($blacklist) {
        $blacklist_id = $blacklist->getId();

        $this->assertTrue((strlen($blacklist->getId()) > 0));

        $blacklist->remove();

        $this->assertTrue((strlen($blacklist->getId()) == 0));

//        $blacklist($blacklist_id)->fetch();
    }

}
