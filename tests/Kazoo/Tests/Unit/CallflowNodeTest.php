<?php

namespace Kazoo\Tests\Unit;

use stdClass;
use Kazoo\Api\Data\Entity\User;
use Kazoo\Api\Data\Entity\VoicemailBox;
use Kazoo\Api\CallflowNode;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class EntityTest extends \PHPUnit_Framework_TestCase {

    protected $client;
    protected $raw_user_json = '{"apps":{"userportal":{"label":"User Portal","icon":"userportal","api_url":"http://kzapp001:8000/v1"}},"call_forward":{"substitute":true,"enabled":false,"number":"","require_keypress":false,"keep_caller_id":false,"direct_calls_only":false,"ignore_early_media":true},"call_restriction":{"closed_groups":{"action":"inherit"},"tollfree_us":{"action":"inherit"},"toll_us":{"action":"inherit"},"emergency":{"action":"inherit"},"caribbean":{"action":"inherit"},"did_us":{"action":"inherit"},"international":{"action":"inherit"},"unknown":{"action":"inherit"}},"caller_id":{"internal":{"name":"Brooklyn Wann"}},"hotdesk":{"enabled":false,"id":"","require_pin":false,"keep_logged_in_elsewhere":false},"contact_list":{"exclude":false},"music_on_hold":{},"username":"brookwann","first_name":"Brooklyn","last_name":"Wann","email":"brooklyn@benwann.com","priv_level":"user","vm_to_email_enabled":false,"fax_to_email_enabled":false,"verified":false,"timezone":"America/Chicago","record_call":false,"enabled":true,"require_password_update":false,"is_sub_account_rep":false,"caller_id_options":{},"media":{},"notifications":{},"id":"985db99c5db1b23b6273183c18462616"}';
    protected $raw_voicemail_json = '{"require_pin":true,"check_if_owner":true,"pin":"2003","media":{},"name":"Brooklyn Wann","mailbox":"2003","owner_id":"985db99c5db1b23b6273183c18462616","timezone":"America/Chicago","is_setup":false,"skip_greeting":false,"skip_instructions":false,"delete_after_notify":false,"notifications":{},"messages":[{"timestamp":63558490658,"from":"2000@sip.benwann.com","to":"2003@sip.benwann.com","caller_id_number":"2000","caller_id_name":"Ben Wann","call_id":"ZTRhMjkxM2Q4YWMwNzFiYjEwZmQyZDkyNGJjZDFiZGQ","folder":"new","length":40,"media_id":"af991ed724f4bb83f222c092fcfd1b50"},{"timestamp":63558480709,"from":"2000@sip.benwann.com","to":"2003@sip.benwann.com","caller_id_number":"2000","caller_id_name":"Ben Wann","call_id":"NDdjMmZjZjQ4ZTVmZTIxNTNkNGNkZjljNzMyNDEyMTE","folder":"new","length":380,"media_id":"7ba5694a4f6bf60f7c48e51da2fa5b20"},{"timestamp":63558469943,"from":"2000@sip.benwann.com","to":"2003@sip.benwann.com","caller_id_number":"2000","caller_id_name":"Ben Wann","call_id":"0bfe12ec965b864083cd1470006f8691@0:0:0:0:0:0:0:0","folder":"new","length":20800,"media_id":"0cde98754c25774c6d19ef6836470796"}],"id":"d091114eb98a8fb216ae00107ac26f05"}';

    public function setUp() {
        $username = 'bwann';
        $password = '12341234';
        $sipRealm = 'sip.benwann.com';
        $options = array();
        $options["base_url"] = "http://192.168.56.111:8000";
        $options["log_type"] = "file";
        $options["log_file"] = "/var/log/kazoo-sdk.log";

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
    public function testCreateCallflowData() {
        
        $user_data = json_decode($this->raw_user_json);
        $user = new User($this->client, "/" . $user_data->id, $user_data);
        
        $voicemail_data = json_decode($this->raw_voicemail_json);
        $vmbox = new VoicemailBox($this->client, "/" . $voicemail_data->id, $voicemail_data);
        
        $ext = rand(2000,9999);
        
        $callflow = $this->client->accounts()->callflows()->new();
        $callflow->setName("New Test Callflow");
        $callflow->addNumber($ext);
        $callflow->addNumber("+1314234" . $ext);
        
        $user_node = new CallflowNode();
        $user_node->setModule($user->getCallflowModuleName());
        $user_node->setDataProperty('timeout', "20");
        $user_node->setDataProperty('can_call_self', false);
        $user_node->setDataProperty('id', $user->id);
        
        $vm_node = new CallflowNode();
        $vm_node->setModule($vmbox->getCallflowModuleName());
        $vm_node->setDataProperty('id', $vmbox->id);
        
        $user_node->addDefaultCallflowElement($vm_node);
        
        $flow = $user_node->renderFlow();

        $this->assertObjectHasAttribute('data', $flow);
        $this->assertObjectHasAttribute('module', $flow);
        $this->assertObjectHasAttribute('children', $flow);
    }

}