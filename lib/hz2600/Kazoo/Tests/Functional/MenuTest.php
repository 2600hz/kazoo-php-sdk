<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class MenuTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateMenu() {
        $menu = $this->getSDK()->Account()->Menu();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Menu", $menu);
        $this->assertTrue((strlen($menu->getId()) == 0));

        $menu->name = "SDK Create Test " . rand(100, 1000);
        $menu->save();

        $this->assertTrue((strlen($menu->getId()) > 0));
        return $menu->getId();
    }

    /**
     * @test
     * @depends testCreateMenu
     */
    public function testFetchMenu($menu_id) {
        $menu = $this->getSDK()->Account()->Menu($menu_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Menu", $menu);
        $this->assertTrue((strlen($menu->getId()) > 0));
        $this->assertEquals($menu->getId(), $menu_id);

        return $menu;
    }

    /**
     * @test
     * @depends testFetchMenu
     */
    public function testUpdateMenu($menu) {
        $menu_id = $menu->getId();
        $current_name = $menu->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $menu->name = $new_name;
        $menu->save();

        // Make sure we didn't create a new menu
        $this->assertEquals($menu->getId(), $menu_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($menu->name, $new_name);

        // Fetch it from the database again to make sure
        $menu->fetch();
        $this->assertEquals($menu->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateMenu
     */
    public function testListingMenus($menu_id) {
        $menus = $this->getSDK()->Account()->Menus();

        $menu = null;
        foreach($menus as $element) {
            if ($element->id == $menu_id) {
                $menu = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Menu", $menu);
        $this->assertTrue((strlen($menu->getId()) > 0));
        $this->assertEquals($menu->getId(), $menu_id);

        return $menu->name;
    }

    /**
     * @test
     * @depends testCreateMenu
     * @depends testListingMenus
     */
    public function testFilteredListingMenus($menu_id, $menu_name) {
        $filter = array('filter_name' => $menu_name);
        $menus = $this->getSDK()->Account()->Menus($filter);

        $this->assertTrue(count($menus) == 1);

        $element = $menus->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $menu_id);

        $filter = array('filter_name' => 'no-such-menu');
        $menus = $this->getSDK()->Account()->Menus($filter);

        $this->assertTrue(count($menus) == 0);
    }

    /**
     * @test
     * @depends testFetchMenu
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildMenu($menu) {
        $menu_id = $menu->getId();

        $this->assertTrue((strlen($menu->getId()) > 0));

        $menu->remove();

        $this->assertTrue((strlen($menu->getId()) == 0));

//        $menu($menu_id)->fetch();
    }

}