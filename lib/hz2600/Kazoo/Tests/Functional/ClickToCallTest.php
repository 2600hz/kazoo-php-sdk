<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class ClickToCallTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateClickToCall() {
        $clicktocall = $this->getSDK()->Account()->Clicktocall();

        $this->markTestIncomplete(
            'This test requires live calls'
        );

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Clicktocall", $clicktocall);
        $this->assertTrue((strlen($clicktocall->getId()) == 0));

        $clicktocall->name = "SDK Create Test " . rand(100, 1000);
        $clicktocall->extension= "15342";
        $clicktocall->save();

        $this->assertTrue((strlen($clicktocall->getId()) > 0));
        return $clicktocall->getId();
    }

    /**
     * @test
     * @depends testCreateClicktocall
     */
    public function testFetchClicktocall($clicktocall_id) {
        $clicktocall = $this->getSDK()->Account()->Clicktocall($clicktocall_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Clicktocall", $clicktocall);
        $this->assertTrue((strlen($clicktocall->getId()) > 0));
        $this->assertEquals($clicktocall->getId(), $clicktocall_id);

        return $clicktocall;
    }

    /**
     * @test
     * @depends testFetchClicktocall
     */
    public function testUpdateClicktocall($clicktocall) {
        $clicktocall_id = $clicktocall->getId();
        $current_name = $clicktocall->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $clicktocall->name = $new_name;
        $clicktocall->save();

        // Make sure we didn't create a new clicktocall
        $this->assertEquals($clicktocall->getId(), $clicktocall_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($clicktocall->name, $new_name);

        // Fetch it from the database again to make sure
        $clicktocall->fetch();
        $this->assertEquals($clicktocall->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateClicktocall
     */
    public function testListingClicktocalls($clicktocall_id) {
        $clicktocalls = $this->getSDK()->Account()->Clicktocalls();

        $clicktocall = null;
        foreach($clicktocalls as $element) {
            if ($element->id == $clicktocall_id) {
                $clicktocall = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Clicktocall", $clicktocall);
        $this->assertTrue((strlen($clicktocall->getId()) > 0));
        $this->assertEquals($clicktocall->getId(), $clicktocall_id);

        return $clicktocall->name;
    }

    /**
     * @test
     * @depends testCreateClicktocall
     * @depends testListingClicktocalls
     */
    public function testFilteredListingClicktocalls($clicktocall_id, $clicktocall_name) {
        $filter = array('filter_name' => $clicktocall_name);
        $clicktocalls = $this->getSDK()->Account()->Clicktocalls($filter);

        $this->assertTrue(count($clicktocalls) == 1);

        $element = $clicktocalls->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $clicktocall_id);

        $filter = array('filter_name' => 'no-such-clicktocall');
        $clicktocalls = $this->getSDK()->Account()->clicktocalls($filter);

        $this->assertTrue(count($clicktocalls) == 0);
    }

    /**
     * @test
     * @depends testFetchClicktocall
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildClicktocall($clicktocall) {
        $clicktocall_id = $clicktocall->getId();

        $this->assertTrue((strlen($clicktocall->getId()) > 0));

        $clicktocall->remove();

        $this->assertTrue((strlen($clicktocall->getId()) == 0));

//        $clicktocall($clicktocall_id)->fetch();
    }

}
