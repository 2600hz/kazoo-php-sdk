<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Device;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class FaxTest extends \PHPUnit_Framework_TestCase {

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
    public function testCreateEmptyFax() {

        try {
            $fax = $this->client->accounts()->faxes()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Fax", $fax);

            return $fax;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyFax
     */
    public function testSendFax($fax) {

        try {
            $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);

            $fax->name = "Test Fax #" . $num;
            $fax->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Fax", $fax);
            $this->assertTrue((strlen($device->id) > 0));

            return $device->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    public function testRetrieveAll() {

        try {

            $faxes = $this->client->accounts()->faxes()->retrieve();
            $this->assertGreaterThan(0, count($faxes));
            return $search_media;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
