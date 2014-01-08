<?php

namespace Kazoo\Tests\Unit;

use stdClass;
use Kazoo\Api\Data\Entity\Device;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class EntityTest extends \PHPUnit_Framework_TestCase {

    protected $client;
    protected $raw_device_json = '{"name":"benwann.com","realm":"sip.benwann.com","id":"4b31dd1d32ce6d249897c06332375d65","timezone":"America/Los_Angeles","caller_id":{},"caller_id_options":{},"notifications":{"first_occurrence":{"sent_initial_registration":"true"}},"media":{"bypass_media":"auto"},"music_on_hold":{},"available_apps":{"0":"voip","1":"cluster","2":"userportal","3":"accounts","4":"developer","5":"pbxs","6":"numbers"},"wnm_allow_additions":"true","superduper_admin":"true","created":"63550068513","billing_mode":"limits_only"}';

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
    public function shouldGetAndSetProperties() {
        
        $data = json_decode($this->raw_device_json);
        $device = new Device($this->client, "/" . $data->id, $data);
        
        $device->test = "value";
        $device->available_apps->new_obj = new stdClass();
        $device->available_apps->new_obj->list = array();
        $device->available_apps->new_obj->list[] = "cellphone";
        $device->available_apps->new_obj->list[] = "deskphone";
        $device->available_apps->new_obj->list[] = "pager";
        
//        echo $device . "\n";
        echo $device->test . "\n";
        die();

        $this->assertObjectHasAttribute('test', $device);
        $this->assertObjectHasAttribute('available_apps', $device);
        $this->assertObjectHasAttribute('new_obj', $device->available_apps);
        $this->assertObjectHasAttribute('list', $device->available_apps->new_obj);
        $this->assertCount(3, $device->available_apps->new_obj->list);
    }

}