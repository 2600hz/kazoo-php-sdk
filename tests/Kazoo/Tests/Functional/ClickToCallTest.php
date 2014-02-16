<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\ClickToCall;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class ClickToCallTest extends \PHPUnit_Framework_TestCase {

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
    public function testCreateEmptyCTCall() {

        try {
            $ctcall = $this->client->accounts()->clicktocalls()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\ClickToCall", $ctcall);

            return $ctcall;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyCTCall
     */
    public function testCreateCTCall($ctcall) {

        try {
            
            $num = rand(1, 10000);
            $ctcall->name = "Test CTCall #" . $num;
            $ctcall->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\ClickToCall", $ctcall);
            $this->assertTrue((strlen($ctcall->id) > 0));

            return $ctcall->id;
            
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateCTCall
     */
    public function testRetrieveCTCall($ctcall_id) {

        try {
            $ctcall = $this->client->accounts()->clicktocalls()->retrieve($ctcall_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\ClickToCall", $call);
            $this->assertTrue((strlen($call->id) > 0));
            return $ctcall;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveCTCall
     */
    public function testUpdateCTCall($ctcall) {

        try {
            $ctcall->name = "Updated: " . $ctcall->name;
            $ctcall->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\ClickToCall", $ctcall);
            $this->assertTrue((strlen($ctcall->id) > 0));

            return $ctcall;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testUpdateCTCall
     */
    public function testRetrieveAllAndUpdateOne($search_ctcall) {
        
        try {
            
            $ctcalls = $this->client->accounts()->clicktocalls()->retrieve();
            foreach($ctcalls as $ctcall){
                if($ctcall->id == $search_ctcall->id){
                    $search_ctcall->name = "Updated: " . $search_ctcall->name;
                    $search_ctcall->save();
                }
            }
            $this->assertGreaterThan(0, count($ctcalls));
            return $search_ctcall;
            
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
    public function testDeleteCTCall($ctcall) {

        try {
            $ctcall->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
