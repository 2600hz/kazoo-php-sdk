<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class UserTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateUser() {
        $user = $this->getSDK()->Account()->User();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\User", $user);
        $this->assertTrue((strlen($user->getId()) == 0));

        $user->first_name = "SDK Create First " . rand(100, 1000);
        $user->last_name = "SDK Create Last " . rand(100, 1000);
        $user->save();

        $this->assertTrue((strlen($user->getId()) > 0));
        return $user->getId();
    }

    /**
     * @test
     * @depends testCreateUser
     */
    public function testFetchUser($user_id) {
        $user = $this->getSDK()->Account()->User($user_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\User", $user);
        $this->assertTrue((strlen($user->getId()) > 0));
        $this->assertEquals($user->getId(), $user_id);

        return $user;
    }

    /**
     * @test
     * @depends testFetchUser
     */
    public function testUpdateUser($user) {
        $user_id = $user->getId();
        $current_name = $user->first_name;
        $new_name = "SDK Update First " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $user->first_name = $new_name;
        $user->save();

        // Make sure we didn't create a new user
        $this->assertEquals($user->getId(), $user_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($user->first_name, $new_name);

        // Fetch it from the database again to make sure
        $user->fetch();
        $this->assertEquals($user->first_name, $new_name);
    }

    /**
     * @test
     * @depends testCreateUser
     */
    public function testListingUsers($user_id) {
        $users = $this->getSDK()->Account()->Users();

        $user = null;
        foreach($users as $element) {
            if ($element->id == $user_id) {
                $user = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\User", $user);
        $this->assertTrue((strlen($user->getId()) > 0));
        $this->assertEquals($user->getId(), $user_id);

        return $user->first_name;
    }

    /**
     * @test
     * @depends testCreateUser
     * @depends testListingUsers
     */
    public function testFilteredListingUsers($user_id, $user_name) {
        $filter = array('filter_first_name' => $user_name);
        $users = $this->getSDK()->Account()->Users($filter);

        $this->assertTrue(count($users) == 1);

        $element = $users->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $user_id);

        $filter = array('filter_first_name' => 'no-such-user');
        $users = $this->getSDK()->Account()->Users($filter);

        $this->assertTrue(count($users) == 0);
    }

    /**
     * @test
     * @depends testFetchUser
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildUser($user) {
        $user_id = $user->getId();

        $this->assertTrue((strlen($user->getId()) > 0));

        $user->remove();

        $this->assertTrue((strlen($user->getId()) == 0));

//        $user($user_id)->fetch();
    }
}
