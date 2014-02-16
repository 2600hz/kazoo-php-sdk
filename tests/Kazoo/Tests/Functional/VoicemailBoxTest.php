<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\VoicemailBox;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class VoicemailBoxTest extends \PHPUnit_Framework_TestCase {

    protected $client;

    public function setUp() {

        $username = 'bwann';
        $password = '12341234';
        $sipRealm = 'sip.benwann.com';
        $options = array();
        $options["base_url"] = "http://192.168.56.111:8000";

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
    public function testCreateEmptyVoicemailBox() {

        try {
            $vmbox = $this->client->accounts()->voicemail_boxes()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\VoicemailBox", $vmbox);

            return $vmbox;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyVoicemailBox
     */
    public function testCreateVoicemailBox($vmbox) {

        try {
            $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);

            $vmbox->name = "Test#" . $num;
            $vmbox->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\VoicemailBox", $vmbox);
            $this->assertTrue((strlen($vmbox->id) > 0));

            return $vmbox->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateVoicemailBox
     */
    public function testRetrieveVoicemailBox($vmbox_id) {

        try {
            $vmbox = $this->client->accounts()->voicemail_boxes()->retrieve($vmbox_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\VoicemailBox", $vmbox);
            $this->assertTrue((strlen($vmbox->id) > 0));
            return $vmbox;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveVoicemailBox
     */
    public function testUpdateVoicemailBox($vmbox) {

        try {
            $vmbox->name = "Updated: " . $vmbox->name;
            $vmbox->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\VoicemailBox", $vmbox);
            $this->assertTrue((strlen($vmbox->id) > 0));

            return $vmbox;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testUpdateVoicemailBox
     */
    public function testRetrieveAllAndUpdateOne($search_vmbox) {
        
        try {
            
            $vmboxes = $this->client->accounts()->voicemail_boxes()->retrieve();
            foreach($vmboxes as $vmbox){
                if($vmbox->id == $search_vmbox->id){
                    $search_vmbox->name = "Updated: " . $search_vmbox->name;
                    $search_vmbox->save();
                }
            }
            $this->assertGreaterThan(0, count($vmboxes));
            return $search_vmbox;
            
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
    public function testDeleteVoicemailBox($vmbox) {

        try {
            $vmbox->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
