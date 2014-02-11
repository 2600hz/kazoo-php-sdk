<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Callflow;
use Kazoo\Api\Data\Entity\User;
use Kazoo\Api\Data\Entity\VoicemailBox;
use Kazoo\Api\CallflowNode;
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
        $options  = array();
        $options["base_url"] = "http://192.168.0.111:8000";
        $options["log_type"] = "file";
        $options["log_file"] = "/var/log/kazoo-sdk.log";
        $this->test_user_id = "204f27a1a5a62142a607b5489462c873";
        $this->test_vmbox_id = "04758ef7cacb2b42f1dc88211d8ae250";

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
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testCreateEmptyShell
     */
    public function testCreateCallflow($callflow){
        $user = $this->client->accounts()->users()->retrieve($this->test_user_id);
        $vmbox = $this->client->accounts()->voicemail_boxes()->retrieve($this->test_vmbox_id);
        
        $ext = rand(2000,9999);
        $callflow->name = $ext . " - New Test Callflow";
        $callflow->addNumber((string)$ext);
        $callflow->addNumber("+1314234" . $ext);
        
        $user_node = new CallflowNode();
        $user_node->setModule($user->getCallflowModuleName());
        $user_node->setDataProperty('timeout', "20");
        $user_node->setDataProperty('can_call_self', false);
        $user_node->setDataProperty('id', $user->id);
        
        $vm_node = new CallflowNode();
        $vm_node->setModule($vmbox->getCallflowModuleName());
        $vm_node->setDataProperty('id', $vmbox->id);
        
        $user_node->addDefaultChild($vm_node);
        
        $flow = $user_node->renderFlow();
        
        $callflow->flow = $flow;
        $callflow->save();
        die();
    }
}
