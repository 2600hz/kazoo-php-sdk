<?php

namespace Kazoo\Tests\Special;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group special
 */
class ResourceTemplateTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateResourceTemplate() {
        $resourcetemplate = $this->getSDK()->Account()->ResourceTemplate();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\ResourceTemplate", $resourcetemplate);
        $this->assertTrue((strlen($resourcetemplate->getId()) == 0));

        $resourcetemplate->template_name = "SDK Create Test ";
        $resourcetemplate->save();

        $this->assertTrue((strlen($resourcetemplate->getId()) > 0));
        return $resourcetemplate->getId();
    }

    /**
     * @test
     * @depends testCreateResourceTemplate
     */
    public function testFetchResourceTemplate($resourcetemplate_id) {
        $resourcetemplate = $this->getSDK()->Account()->ResourceTemplate($resourcetemplate_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\ResourceTemplate", $resourcetemplate);
        $this->assertTrue((strlen($resourcetemplate->getId()) > 0));
        $this->assertEquals($resourcetemplate->getId(), $resourcetemplate_id);

        return $resourcetemplate;
    }

    /**
     * @test
     * @depends testFetchResourceTemplate
     */
    public function testUpdateResourceTemplate($resourcetemplate) {
        $resourcetemplate_id = $resourcetemplate->getId();
        $current_name = $resourcetemplate->template_name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $resourcetemplate->template_name = $new_name;
        $resourcetemplate->save();

        // Make sure we didn't create a new resourcetemplate
        $this->assertEquals($resourcetemplate->getId(), $resourcetemplate_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($resourcetemplate->template_name, $new_name);

        // Fetch it from the database again to make sure
        $resourcetemplate->fetch();
        $this->assertEquals($resourcetemplate->template_name, $new_name);
    }

    /**
     * @test
     * @depends testCreateResourceTemplate
     * @depends testUpdateResourceTemplate
     */
    public function testListingResourceTemplates($resourcetemplate_id) {
        $resourcetemplates = $this->getSDK()->Account()->ResourceTemplates();

        $resourcetemplate = null;
        foreach($resourcetemplates as $element) {
            if ($element->id == $resourcetemplate_id) {
                $resourcetemplate = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\ResourceTemplate", $resourcetemplate);
        $this->assertTrue((strlen($resourcetemplate->getId()) > 0));
        $this->assertEquals($resourcetemplate->getId(), $resourcetemplate_id);

        return $resourcetemplate->name;
    }

    /**
     * @test
     * @depends testCreateResourceTemplate
     * @depends testListingResourceTemplates
     */
    public function testFilteredListingResourceTemplates($resourcetemplate_id) {
        $filter = array('filter_id' => $resourcetemplate_id);
        $resourcetemplates = $this->getSDK()->Account()->ResourceTemplates($filter);

        $this->assertTrue(count($resourcetemplates) == 1);

        $element = $resourcetemplates->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $resourcetemplate_id);

        $filter = array('filter_id' => 'no-such-id');
        $resourcetemplates = $this->getSDK()->Account()->ResourceTemplates($filter);

        $this->assertTrue(count($resourcetemplates) == 0);
    }

    /**
     * @test
     * @depends testFetchResourceTemplate
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildResourceTemplate($resourcetemplate) {
        $resourcetemplate_id = $resourcetemplate->getId();

        $this->assertTrue((strlen($resourcetemplate->getId()) > 0));

        $resourcetemplate->remove();

        $this->assertTrue((strlen($resourcetemplate->getId()) == 0));

//        $resourcetemplate($resourcetemplatewq_id)->fetch();
    }

}
