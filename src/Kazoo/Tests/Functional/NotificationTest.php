<?php

/**
 * Requires:
 *   Kazoo 3.19+
 *     sup crossbar_maintenance start_module cb_notifications // enable the API endpont
 *     sup whapps_controller start_app teletype               // load system notification templates
 */

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class NotificationTest extends FunctionalTest
{
    // don't run tests against the primary account, it will delete system notifications
    private $account_id = '0a936fc79bdb4a8c38e6089ab44ad030';

    /**
     * @test
     * Plays with adding the skel notification template
     */
    public function testId() {
        $this->assertTrue(TRUE);
        return "skel";
    }

    /**
     * @test
     * This is disabled as notifications are created by the system only
     */
    /**
    public function testCreateNotification() {
    }
    */

    /**
     * @test
     * @depends testId
     */
    public function testListingNotifications($notification_id) {
        $notifications = $this->getSDK()->Account()->Notifications();

        $notification = null;
        foreach($notifications as $element) {
            if ($element->id == $notification_id) {
                $notification = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Notification", $notification);
        $this->assertTrue((strlen($notification->getId()) > 0));
        $this->assertEquals($notification->getId(), $notification_id);

        return $notification->friendly_name;
    }

    /**
     * @test
     * @depends testId
     */
    public function testFetchNotification($notification_id) {
        $notification = $this->getSDK()->Account($this->account_id)->Notification($notification_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Notification", $notification);
        $this->assertTrue((strlen($notification->getId()) > 0));
        $this->assertEquals($notification->getId(), $notification_id);

        return $notification;
    }

    /**
     * @test
     * @depends testFetchNotification
     */
    public function testUpdateNotification($notification) {
        $notification_id = $notification->getId();
        $current_name = $notification->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $notification->name = $new_name;
        $notification->save();

        // Make sure we didn't create a new notification
        $this->assertEquals($notification->getId(), $notification_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($notification->name, $new_name);

        // Fetch it from the database again to make sure
        $notification->fetch();
        $this->assertEquals($notification->name, $new_name);
    }

    /**
     * @test
     * @depends testId
     * @depends testListingNotifications
     */
    public function testFilteredListingNotifications($notification_id, $notification_name) {
        $filter = array('filter_friendly_name' => $notification_name);
        $notifications = $this->getSDK()->Account($this->account_id)->Notifications($filter);

        $this->assertTrue(count($notifications) == 1);

        $element = $notifications->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $notification_id);

        $filter = array('filter_friendly_name' => 'no-such-notification');
        $notifications = $this->getSDK()->Account($this->account_id)->Notifications($filter);

        $this->assertTrue(count($notifications) == 0);
    }

    /**
     * @test
     * @depends testFetchNotification
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildNotification($notification) {
        $notification_id = $notification->getId();

        $this->assertTrue((strlen($notification->getId()) > 0));

        $notification->remove();

        $this->assertTrue((strlen($notification->getId()) == 0));

//        $notification($notification_id)->fetch();
    }

}
