<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class FaxesTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateFax() {
        $fax = $this->getSDK()->Account()->Fax();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Fax", $fax);
        $this->assertTrue((strlen($fax->getId()) == 0));

        $fax->from_name = "SDK Create Test " . rand(100, 1000);
        $fax->save();

        $this->assertTrue((strlen($fax->getId()) > 0));
        return $fax->getId();
    }

    /**
     * @test
     * @depends testCreateFax
     */
    public function testFetchFax($fax_id) {
        $fax = $this->getSDK()->Account()->Fax($fax_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Fax", $fax);
        $this->assertTrue((strlen($fax->getId()) > 0));
        $this->assertEquals($fax->getId(), $fax_id);

        return $fax;
    }

    /**
     * @test
     * @depends testFetchFax
     */
    public function testUpdateFax($fax) {
        $fax_id = $fax->getId();
        $current_name = $fax->from_name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $fax->from_name = $new_name;
        $fax->save();

        // Make sure we didn't create a new fax
        $this->assertEquals($fax->getId(), $fax_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($fax->from_name, $new_name);

        // Fetch it from the database again to make sure
        $fax->fetch();
        $this->assertEquals($fax->from_name, $new_name);
    }

    /**
     * @test
     * @depends testCreateFax
     */
    public function testListingFaxes($fax_id) {
        $faxes = $this->getSDK()->Account()->Faxes();

        $fax = null;
        foreach($faxes as $element) {
            if ($element->id == $fax_id) {
                $fax = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Fax", $fax);
        $this->assertTrue((strlen($fax->getId()) > 0));
        $this->assertEquals($fax->getId(), $fax_id);

        return $fax->from_name;
    }


    /**
     * @test
     * @depends testCreateFax
     * @depends testListingFaxes
     */
    public function testFilteredListingFaxes($fax_id) {
        $filter = array('filter_id' => $fax_id);
        $faxes = $this->getSDK()->Account()->Faxes($filter);

        $this->assertTrue(count($faxes) == 1);

        $element = $faxes->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $fax_id);

        $filter = array('filter_id' => 'no-such-faxes');
        $faxes = $this->getSDK()->Account()->Faxes($filter);

        $this->assertTrue(count($faxes) == 0);
    }

    /**
     * @test
     * @depends testFetchFax
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildFaxes($fax) {
        $fax_id = $fax->getId();

        $this->assertTrue((strlen($fax->getId()) > 0));

        $fax->remove();

        $this->assertTrue((strlen($fax->getId()) == 0));

//        $faxes($fax_id)->fetch();
    }

}
