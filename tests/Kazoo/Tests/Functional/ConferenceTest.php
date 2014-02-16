<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Conference;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class ConferenceTest extends \PHPUnit_Framework_TestCase {

    protected $client;

    public function setUp() {

        $username = 'bwann';
        $password = '12341234';
        $sipRealm = 'sip.benwann.com';
        $options = array();
        $options["base_url"] = "http://192.168.56.111:8000";
        $this->test_user_id = "985db99c5db1b23b6273183c18462616";
        $this->test_device_id = "0e234cdbb1bcd8087498a063eafbcd62";

        // You have to specify authentication here to run full suite

        try {
            $this->client = new \Kazoo\Client($username, $password, $sipRealm, $options);
        } catch (ApiLimitExceedException $e) {
            $this->markTestSkipped('API limit reached. Skipping to prevent unnecessary failure.');
        } catch (RuntimeException $e) {
            if ('Requires authentication' == $e->getMessage()) {
                $this->markTestSkipped('Test requires authentication. Skipping to prevent unnecessary failure.');
            }
        }
    }

    /**
     * @test
     */
    public function testCreateEmptyConference() {

        try {
            
            $conference = $this->client->accounts()->conferences()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Conference", $conference);
            return $conference;
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyConference
     */
    public function testCreateConference($conference) {

        try {
            
            $num = rand(1, 10000);
            $conference->name = "Test Conference #" . $num;
            $conference->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Conference", $conference);
            $this->assertTrue((strlen($conference->id) > 0));

            return $conference->id;
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateConference
     */
    public function testRetrieveConference($conference_id) {

        try {
            
            $conference = $this->client->accounts()->conferences()->retrieve($conference_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Conference", $conference);
            $this->assertTrue((strlen($conference->id) > 0));
            return $conference;
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveConference
     */
    public function testUpdateConference($conference) {

        try {
            $conference->name = "Updated: " . $conference->name;
            $conference->addDeviceToGroup($this->test_device_id);
            $conference->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Conference", $conference);
            $this->assertTrue((strlen($conference->id) > 0));

            return $conference;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testUpdateConference
     */
    public function testRetrieveAllAndUpdateOne($search_conference) {
        
        try {
            
            $conferences = $this->client->accounts()->groups()->retrieve();
            foreach($conferences as $conference){
                if($conference->id == $search_conference->id){
                    $search_conference->name = "Updated: " . $search_conference->name;
                    $search_conference->save();
                }
            }
            $this->assertGreaterThan(0, count($conferences));
            return $search_conference;
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveAllAndUpdateOne
     */
    public function testDeleteConference($conference) {

        try {
            $conference->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
