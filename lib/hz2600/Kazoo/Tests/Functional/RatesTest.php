<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;


/**
 * @rate functional
 */
class RateTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateRate() {
        $rate = $this->getSDK()->Account()->Rate();
        $this->markTestIncomplete(
            'This test requires rates to be enabled'
        );

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Rate", $rate);
        $this->assertTrue((strlen($rate->getId()) == 0));

        $rate->prefix = rand(100,999);
        $rate->rate_cost = 1;
        $rate->save();

        $this->assertTrue((strlen($rate->getId()) > 0));
        return $rate->getId();
    }

    /**
     * @test
     * @depends testCreateRate
     */
    public function testFetchRate($rate_id) {
        $rate = $this->getSDK()->Account()->Rate($rate_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Rate", $rate);
        $this->assertTrue((strlen($rate->getId()) > 0));
        $this->assertEquals($rate->getId(), $rate_id);

        return $rate;
    }

    /**
     * @test
     * @depends testFetchRate
     */
    public function testUpdateRate($rate) {
        $rate_id = $rate->getId();
        $current_prefix = $rate->prefix;
        $new_prefix = rand(100,999);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_prefix, $new_prefix);

        $rate->prefix = $new_prefix;
        $rate->save();

        // Make sure we didn't create a new rate
        $this->assertEquals($rate->getId(), $rate_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($rate->prefix, $new_prefix);

        // Fetch it from the database again to make sure
        $rate->fetch();
        $this->assertEquals($rate->prefix, $new_prefix);
    }

    /**
     * @test
     * @depends testCreateRate
     */
    public function testListingRates($rate_id) {
        $this->markTestSkipped('Until Fix https://2600hz.atlassian.net/browse/KAZOO-3939');
        $rates = $this->getSDK()->Account()->Rates();
        $rate = null;
        foreach($rates as $element) {
            $myel = $element->fetch();
            if ($element->id == $rate_id) {
                $rate = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Rate", $rate);
        $this->assertTrue((strlen($rate->getId()) > 0));
        $this->assertEquals($rate->getId(), $rate_id);

        return $rate->name;
    }

    /**
     * @test
     * @depends testCreateRate
     * @depends testListingRates
     */
    public function testFilteredListingRates($rate_id, $rate_prefix) {
        $filter = array('filter_prefix' => $rate_prefix);
        $rates = $this->getSDK()->Account()->Rates($filter);

        $this->assertTrue(count($rates) == 1);

        $element = $rates->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $rate_id);

        $filter = array('filter_prefix' => 'no-such-rate');
        $rates = $this->getSDK()->Account()->rates($filter);

        $this->assertTrue(count($rates) == 0);
    }

    /**
     * @test
     * @depends testFetchRate
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildRate($rate) {
        $rate_id = $rate->getId();

        $this->assertTrue((strlen($rate->getId()) > 0));

        $rate->remove();

        $this->assertTrue((strlen($rate->getId()) == 0));

//        $rate($rate_id)->fetch();
    }

}
