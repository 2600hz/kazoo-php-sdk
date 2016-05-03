<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class TemporalRulesTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateTemporalRule() {
        $this->markTestIncomplete(
            'This test requires temporal rule app to be running'
        );
        $temporalrule = $this->getSDK()->Account()->TemporalRule();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\TemporalRule", $temporalrule);
        $this->assertTrue((strlen($temporalrule->getId()) == 0));

        $temporalrule->name = "SDK Create Test " . rand(100, 1000);
        $temporalrule->cycle="weekly";
        $temporalrule->save();

        $this->assertTrue((strlen($temporalrule->getId()) > 0));
        return $temporalrule->getId();
    }

    /**
     * @test
     * @depends testCreateTemporalRule
     */
    public function testFetchTemporalRule($temporalrule_id) {
        $temporalrule = $this->getSDK()->Account()->TemporalRule($temporalrule_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\TemporalRule", $temporalrule);
        $this->assertTrue((strlen($temporalrule->getId()) > 0));
        $this->assertEquals($temporalrule->getId(), $temporalrule_id);

        return $temporalrule;
    }

    /**
     * @test
     * @depends testFetchTemporalRule
     */
    public function testUpdateTemporalRule($temporalrule) {
        $temporalrule_id = $temporalrule->getId();
        $current_name = $temporalrule->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $temporalrule->name = $new_name;
        $temporalrule->save();

        // Make sure we didn't create a new temporalrule
        $this->assertEquals($temporalrule->getId(), $temporalrule_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($temporalrule->name, $new_name);

        // Fetch it from the database again to make sure
        $temporalrule->fetch();
        $this->assertEquals($temporalrule->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateTemporalRule
     */
    public function testListingTemporalRules($temporalrule_id) {
        $temporalrules = $this->getSDK()->Account()->TemporalRules();

        $temporalrule = null;
        foreach($temporalrules as $element) {
            if ($element->id == $temporalrule_id) {
                $temporalrule = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\TemporalRule", $temporalrule);
        $this->assertTrue((strlen($temporalrule->getId()) > 0));
        $this->assertEquals($temporalrule->getId(), $temporalrule_id);

        return $temporalrule->name;
    }

    /**
     * @test
     * @depends testCreateTemporalRule
     * @depends testListingTemporalRules
     */
    public function testFilteredListingTemporalRules($temporalrule_id, $temporalrule_name) {
        $filter = array('filter_name' => $temporalrule_name);
        $temporalrules = $this->getSDK()->Account()->TemporalRules($filter);

        $this->assertTrue(count($temporalrules) == 1);

        $element = $temporalrules->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $temporalrule_id);

        $filter = array('filter_name' => 'no-such-temporalrules');
        $temporalrules = $this->getSDK()->Account()->TemporalRules($filter);

        $this->assertTrue(count($temporalrules) == 0);
    }

    /**
     * @test
     * @depends testFetchTemporalRules
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildTemporalRules($temporalrule) {
        $temporalrules_id = $temporalrule->getId();

        $this->assertTrue((strlen($temporalrule->getId()) > 0));

        $temporalrules->remove();

        $this->assertTrue((strlen($temporalrule->getId()) == 0));

//        $temporalrules($temporalrule_id)->fetch();
    }

}
