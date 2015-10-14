<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class TemporalRulesSetTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateTemporalRulesSet() {
        $temporalrulesset = $this->getSDK()->Account()->TemporalRulesSet();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\TemporalRulesSet", $temporalrulesset);
        $this->assertTrue((strlen($temporalrulesset->getId()) == 0));

        $temporalrulesset->name = "SDK Create Test " . rand(100, 1000);
        $temporalrulesset->save();

        $this->assertTrue((strlen($temporalrulesset->getId()) > 0));
        return $temporalrulesset->getId();
    }

    /**
     * @test
     * @depends testCreateTemporalRulesSet
     */
    public function testFetchTemporalRulesSet($temporalrulesset_id) {
        $temporalrulesset = $this->getSDK()->Account()->TemporalRulesSet($temporalrulesset_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\TemporalRulesSet", $temporalrulesset);
        $this->assertTrue((strlen($temporalrulesset->getId()) > 0));
        $this->assertEquals($temporalrulesset->getId(), $temporalrulesset_id);

        return $temporalrulesset;
    }

    /**
     * @test
     * @depends testFetchTemporalRulesSet
     */
    public function testUpdateTemporalRulesSet($temporalrulesset) {
        $temporalrulesset_id = $temporalrulesset->getId();
        $current_name = $temporalrulesset->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $temporalrulesset->name = $new_name;
        $temporalrulesset->save();

        // Make sure we didn't create a new temporalrulesset
        $this->assertEquals($temporalrulesset->getId(), $temporalrulesset_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($temporalrulesset->name, $new_name);

        // Fetch it from the database again to make sure
        $temporalrulesset->fetch();
        $this->assertEquals($temporalrulesset->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateTemporalRulesSet
     */
    public function testListingTemporalRulesSets($temporalrulesset_id) {
        $temporalrulessets = $this->getSDK()->Account()->TemporalRulesSets();

        $temporalrulesset = null;
        foreach($temporalrulessets as $element) {
            if ($element->id == $temporalrulesset_id) {
                $temporalrulesset = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\TemporalRulesSet", $temporalrulesset);
        $this->assertTrue((strlen($temporalrulesset->getId()) > 0));
        $this->assertEquals($temporalrulesset->getId(), $temporalrulesset_id);

        return $temporalrulesset->name;
    }

    /**
     * @test
     * @depends testCreateTemporalRulesSet
     * @depends testListingTemporalRulesSets
     */
    public function testFilteredListingTemporalRulesSets($temporalrulesset_id, $temporalrulesset_name) {
        $filter = array('filter_name' => $temporalrulesset_name);
        $temporalrulessets = $this->getSDK()->Account()->TemporalRulesSets($filter);

        $this->assertTrue(count($temporalrulessets) == 1);

        $element = $temporalrulessets->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $temporalrulesset_id);

        $filter = array('filter_name' => 'no-such-temporalrulesset');
        $temporalrulessets = $this->getSDK()->Account()->TemporalRulesSets($filter);

        $this->assertTrue(count($temporalrulessets) == 0);
    }

    /**
     * @test
     * @depends testFetchTemporalRulesSet
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildTemporalRulesSet($temporalrulesset) {
        $temporalrulesset_id = $temporalrulesset->getId();

        $this->assertTrue((strlen($temporalrulesset->getId()) > 0));

        $temporalrulesset->remove();

        $this->assertTrue((strlen($temporalrulesset->getId()) == 0));

//        $temporalrulesset($temporalrulesset_id)->fetch();
    }

}
