<?php

namespace Kazoo\Tests\Functional;

use StdClass;
use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class ConnectivityTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateConnectivity()
    {
        $account = $this->getSDK()->Account();
        $realm = $account->fetch()->realm;

        $connectivity = $this->getSDK()->Account()->Connectivity();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Connectivity", $connectivity);
        $this->assertTrue((strlen($connectivity->getId()) == 0));

        $connectivity->name = "SDK Create Test " . rand(100, 1000);
        $connectivity->account = new \stdClass();
        $connectivity->account->auth_realm = $realm;
        $connectivity->save();

        $this->assertTrue((strlen($connectivity->getId()) > 0));
        return $connectivity->getId();
    }

    /**
     * @test
     * @depends testCreateConnectivity
     */
    public function testFetchConnectivity($connectivity_id)
    {
        $connectivity = $this->getSDK()->Account()->Connectivity($connectivity_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Connectivity", $connectivity);
        $this->assertTrue((strlen($connectivity->getId()) > 0));
        $this->assertEquals($connectivity->getId(), $connectivity_id);

        return $connectivity;
    }

    /**
     * @test
     * @depends testFetchConnectivity
     */
    public function testUpdateConnectivity($connectivity)
    {
        $connectivity_id = $connectivity->getId();
        $current_name = $connectivity->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $connectivity->name = $new_name;
        $connectivity->save();

        // Make sure we didn't create a new connectivity
        $this->assertEquals($connectivity->getId(), $connectivity_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($connectivity->name, $new_name);

        // Fetch it from the database again to make sure
        $connectivity->fetch();
        $this->assertEquals($connectivity->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateConnectivity
     */
    public function testListingConnectivities($connectivity_id)
    {
        $connectivities = $this->getSDK()->Account()->Connectivities();
        $connectivity = null;
        foreach ($connectivities as $element) {
            $element = $element->fetch();
            if ($element->id == $connectivity_id) {
                $connectivity = $element;
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Connectivity", $connectivity);
        $this->assertTrue((strlen($connectivity->getId()) > 0));
        $this->assertEquals($connectivity->getId(), $connectivity_id);

        return $connectivity->account->auth_realm;
    }

    /**
     * @test
     * @depends testFetchConnectivity
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildConnectivity($connectivity)
    {
        $connectivity_id = $connectivity->getId();

        $this->assertTrue((strlen($connectivity->getId()) > 0));

        $connectivity->remove();

        $this->assertTrue((strlen($connectivity->getId()) == 0));

//        $connectivity($connectivity_id)->fetch();
    }

}
