<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class VMBoxTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateVMBox() {
        $vmbox = $this->getSDK()->Account()->VMBox();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\VMBox", $vmbox);
        $this->assertTrue((strlen($vmbox->getId()) == 0));

        $vmbox->name = "SDK Create Test " . rand(100, 1000);
        $vmbox->mailbox = (string)rand(1000,9999);
        $vmbox->save();

        $this->assertTrue((strlen($vmbox->getId()) > 0));
        return $vmbox->getId();
    }

    /**
     * @test
     * @depends testCreateVMBox
     */
    public function testFetchVMBox($vmbox_id) {
        $vmbox = $this->getSDK()->Account()->VMBox($vmbox_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\VMBox", $vmbox);
        $this->assertTrue((strlen($vmbox->getId()) > 0));
        $this->assertEquals($vmbox->getId(), $vmbox_id);

        return $vmbox;
    }

    /**
     * @test
     * @depends testFetchVMBox
     */
    public function testUpdateVMBox($vmbox) {
        $vmbox_id = $vmbox->getId();
        $current_name = $vmbox->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $vmbox->name = $new_name;
        $vmbox->save();

        // Make sure we didn't create a new vmbox
        $this->assertEquals($vmbox->getId(), $vmbox_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($vmbox->name, $new_name);

        // Fetch it from the database again to make sure
        $vmbox->fetch();
        $this->assertEquals($vmbox->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateVMBox
     */
    public function testListingVMBoxes($vmbox_id) {
        $vmboxes = $this->getSDK()->Account()->VMBoxes();

        $vmbox = null;
        foreach($vmboxes as $element) {
            if ($element->id == $vmbox_id) {
                $vmbox = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\VMBox", $vmbox);
        $this->assertTrue((strlen($vmbox->getId()) > 0));
        $this->assertEquals($vmbox->getId(), $vmbox_id);

        return $vmbox->name;
    }

    /**
     * @test
     * @depends testCreateVMBox
     * @depends testListingVMBoxes
     */
    public function testFilteredListingVMBoxes($vmbox_id, $vmbox_name) {
        $filter = array('filter_name' => $vmbox_name);
        $vmboxes = $this->getSDK()->Account()->VMBoxes($filter);

        $this->assertTrue(count($vmboxes) == 1);

        $element = $vmboxes->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $vmbox_id);

        $filter = array('filter_name' => 'no-such-vmbox');
        $vmboxes = $this->getSDK()->Account()->VMBoxes($filter);

        $this->assertTrue(count($vmboxes) == 0);
    }

    /**
     * @test
     * @depends testFetchVMBox
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildVMBox($vmbox) {
        $vmbox_id = $vmbox->getId();

        $this->assertTrue((strlen($vmbox->getId()) > 0));

        $vmbox->remove();

        $this->assertTrue((strlen($vmbox->getId()) == 0));

    }
}