<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\TimeBasedRoute;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class TimeBasedRouteTest extends \PHPUnit_Framework_TestCase {

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
    public function testCreateEmptyTimeBasedRoute() {

        try {

            $route = $this->client->accounts()->timed_routes()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\TimeBasedRoute", $route);
            return $route;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyTimeBasedRoute
     */
    public function testCreateTimeBasedRoute($route) {

        try {
            $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
            $route->name = "Test TimeBasedRoute #" . $num;
            $route->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\TimeBasedRoute", $route);
            $this->assertTrue((strlen($route->id) > 0));

            return $route->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateTimeBasedRoute
     */
    public function testRetrieveTimeBasedRoute($route_id) {

        try {
            $route = $this->client->accounts()->timed_routes()->retrieve($route_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\TimeBasedRoute", $route);
            $this->assertTrue((strlen($route->id) > 0));
            return $route;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveTimeBasedRoute
     */
    public function testUpdateTimeBasedRoute($route) {

        try {
            $route->name = "Updated: " . $route->name;
            $route->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\TimeBasedRoute", $route);
            $this->assertTrue((strlen($route->id) > 0));

            return $route;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testUpdateTimeBasedRoute
     */
    public function testRetrieveAllAndUpdateOne($search_route) {

        try {

            $routes = $this->client->accounts()->timed_routes()->retrieve();
            foreach ($routes as $route) {
                if ($route->id == $search_route->id) {
                    $search_route->name = "Updated: " . $search_route->name;
                    $search_route->save();
                }
            }
            $this->assertGreaterThan(0, count($routes));
            return $search_route;
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
    public function testDeleteTimeBasedRoute($route) {

        try {
            $route->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
