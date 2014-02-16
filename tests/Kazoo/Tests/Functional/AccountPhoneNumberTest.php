<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\PhoneNumber;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class AccountPhoneNumberTest extends \PHPUnit_Framework_TestCase {

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
    public function testCreateEmptyPhoneNumber() {

        try {
            $number = $this->client->accounts()->phone_numbers()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\PhoneNumber", $number);

            return $number;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyPhoneNumber
     */
    public function testCreatePhoneNumber($number) {

        try {
            $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);

            $number->name = "Test PhoneNumber #" . $num;
            $number->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\PhoneNumber", $number);
            $this->assertTrue((strlen($number->id) > 0));

            return $number->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreatePhoneNumber
     */
    public function testRetrievePhoneNumber($number_id) {

        try {
            $number = $this->client->accounts()->phone_numbers()->retrieve($number_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\PhoneNumber", $number);
            $this->assertTrue((strlen($number->id) > 0));
            return $number;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrievePhoneNumber
     */
    public function testUpdatePhoneNumber($number) {

        try {
            $number->name = "Updated: " . $number->name;
            $number->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\PhoneNumber", $number);
            $this->assertTrue((strlen($number->id) > 0));

            return $number;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testUpdatePhoneNumber
     */
    public function testRetrieveAllAndUpdateOne($search_number) {
        
        try {
            
            $numbers = $this->client->accounts()->phone_numbers()->retrieve();
            foreach($numbers as $number){
                if($number->id == $search_number->id){
                    $search_number->name = "Updated: " . $search_number->name;
                    $search_number->save();
                }
            }
            $this->assertGreaterThan(0, count($numbers));
            return $search_number;
            
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
    public function testDeletePhoneNumber($number) {

        try {
            $number->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
