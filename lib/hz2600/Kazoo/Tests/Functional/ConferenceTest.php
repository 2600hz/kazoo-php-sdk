<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class ConferenceTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateConference() {
        $conference = $this->getSDK()->Account()->Conference();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Conference", $conference);
        $this->assertTrue((strlen($conference->getId()) == 0));

        $conference->name = "SDK Create Test " . rand(100, 1000);
        $conference->save();

        $this->assertTrue((strlen($conference->getId()) > 0));
        return $conference->getId();
    }

    /**
     * @test
     * @depends testCreateConference
     */
    public function testFetchConference($conference_id) {
        $conference = $this->getSDK()->Account()->Conference($conference_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Conference", $conference);
        $this->assertTrue((strlen($conference->getId()) > 0));
        $this->assertEquals($conference->getId(), $conference_id);

        return $conference;
    }

    /**
     * @test
     * @depends testFetchConference
     */
    public function testUpdateConference($conference) {
        $conference_id = $conference->getId();
        $current_name = $conference->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $conference->name = $new_name;
        $conference->save();

        // Make sure we didn't create a new conference
        $this->assertEquals($conference->getId(), $conference_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($conference->name, $new_name);

        // Fetch it from the database again to make sure
        $conference->fetch();
        $this->assertEquals($conference->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateConference
     */
    public function testListingConferences($conference_id) {
        $conferences = $this->getSDK()->Account()->Conferences();

        $conference = null;
        foreach($conferences as $element) {
            if ($element->id == $conference_id) {
                $conference = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Conference", $conference);
        $this->assertTrue((strlen($conference->getId()) > 0));
        $this->assertEquals($conference->getId(), $conference_id);

        return $conference->name;
    }

    /**
     * @test
     * @depends testCreateConference
     * @depends testListingConferences
     */
    public function testFilteredListingConferences($conference_id, $conference_name) {
        $filter = array('filter_name' => $conference_name);
        $conferences = $this->getSDK()->Account()->Conferences($filter);

        $this->assertTrue(count($conferences) == 1);

        $element = $conferences->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $conference_id);

        $filter = array('filter_name' => 'no-such-conference');
        $conferences = $this->getSDK()->Account()->Conferences($filter);

        $this->assertTrue(count($conferences) == 0);
    }

    /**
     * @test
     * @depends testFetchConference
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildConference($conference) {
        $conference_id = $conference->getId();

        $this->assertTrue((strlen($conference->getId()) > 0));

        $conference->remove();

        $this->assertTrue((strlen($conference->getId()) == 0));

//        $conference($conference_id)->fetch();
    }

}
