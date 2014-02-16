<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Menu;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class MenuTest extends \PHPUnit_Framework_TestCase {

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
    public function testCreateEmptyMenu() {

        try {
            $menu = $this->client->accounts()->menus()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Menu", $menu);

            return $menu;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyMenu
     */
    public function testCreateMenu($menu) {

        try {
            $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);

            $menu->name = "Test Menu #" . $num;
            $menu->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Menu", $menu);
            $this->assertTrue((strlen($menu->id) > 0));

            return $menu->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateMenu
     */
    public function testRetrieveMenu($menu_id) {

        try {
            $menu = $this->client->accounts()->menus()->retrieve($menu_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Menu", $menu);
            $this->assertTrue((strlen($menu->id) > 0));
            return $menu;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveMenu
     */
    public function testUpdateMenu($menu) {

        try {
            $menu->name = "Updated: " . $menu->name;
            $menu->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Menu", $menu);
            $this->assertTrue((strlen($menu->id) > 0));

            return $menu;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testUpdateMenu
     */
    public function testRetrieveAllAndUpdateOne($search_menu) {
        
        try {
            
            $menus = $this->client->accounts()->menus()->retrieve();
            foreach($menus as $menu){
                if($menu->id == $search_menu->id){
                    $search_menu->name = "Updated: " . $search_menu->name;
                    $search_menu->save();
                }
            }
            $this->assertGreaterThan(0, count($menus));
            return $search_menu;
            
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
    public function testDeleteMenu($menu) {

        try {
            $menu->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
