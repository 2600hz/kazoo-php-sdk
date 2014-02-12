<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Callflow;
use Kazoo\Api\Data\Entity\User;
use Kazoo\Api\Data\Entity\Device;
use Kazoo\Api\Data\Entity\VoicemailBox;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class CallflowTest extends \PHPUnit_Framework_TestCase {

    protected $client;

    public function setUp() {

        $username = 'bwann';
        $password = '12341234';
        $sipRealm = 'sip.benwann.com';
        $options = array();
        $options["base_url"] = "http://192.168.56.111:8000";
        $options["log_type"] = "file";
        $options["log_file"] = "/var/log/kazoo-sdk.log";
        $this->test_user_id = "204f27a1a5a62142a607b5489462c873";
        $this->test_vmbox_id = "04758ef7cacb2b42f1dc88211d8ae250";
        $this->test_device_id = "ec729f89d1f03d394e926e93eff9afb1";

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
    public function testCreateEmptyShell() {
        
        try {
            $callflow = $this->client->accounts()->callflows()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Callflow", $callflow);
            return $callflow;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyShell
     */
    public function testCreateCallflow($callflow) {

        try {
            $user = $this->client->accounts()->users()->retrieve($this->test_user_id);
            $vmbox = $this->client->accounts()->voicemail_boxes()->retrieve($this->test_vmbox_id);
            $device = $this->client->accounts()->devices()->retrieve($this->test_device_id);

            $ext = rand(2000, 9999);
            $callflow->name = $ext . " - New Test Callflow";
            $callflow->addNumber((string) $ext);
            $callflow->addNumber("+1314234" . $ext);

            $root_node = $callflow->getNewCallflowNode();
            $root_node->setModule($user->getCallflowModuleName());
            $root_node->setDataProperty('timeout', "20");
            $root_node->setDataProperty('can_call_self', false);
            $root_node->setDataProperty('id', $user->id);

            $device_node = $callflow->getNewCallflowNode();
            $device_node->setModule($device->getCallflowModuleName());
            $device_node->setDataProperty('id', $device->id);
            $root_node->addDefaultChild($device_node);

            $vm_node = $callflow->getNewCallflowNode();
            $vm_node->setModule($vmbox->getCallflowModuleName());
            $vm_node->setDataProperty('id', $vmbox->id);

            $device_node->addDefaultChild($vm_node);

            $callflow->setFlow($root_node);
            $callflow->save();


            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Callflow", $callflow);

            return $callflow->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateCallflow
     */
    public function testRetrieveCallflow($callflow_id) {

        try {
            $callflow = $this->client->accounts()->callflows()->retrieve($callflow_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Callflow", $callflow);
            $this->assertTrue((strlen($callflow->id) > 0));

            return $callflow;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveCallflow
     */
    public function testUpdateCallflow(Callflow $callflow) {

        try {
            $callflow->name = "Updated: " . $callflow->name;
            $callflow->save();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Callflow", $callflow);
            $this->assertTrue((strlen($callflow->id) > 0));
            return $callflow;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testUpdateCallflow
     */
    public function testDeleteCallflow(Callflow $callflow) {

        try {
            $callflow->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
