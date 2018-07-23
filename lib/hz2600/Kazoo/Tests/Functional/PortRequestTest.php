<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

use \stdClass;

/**
 * @group functional
 */
class PortRequestTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreatePortRequest() {
        $phone_number="+19139593975";
        $portrequest = $this->getSDK()->Account()->PortRequest();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\PortRequest", $portrequest);
        $this->assertTrue((strlen($portrequest->getId()) == 0));

        $portrequest->name = "SDK Create Test " . rand(100, 1000);
        $portrequest->port_state="unconfirmed";
        $portrequest->numbers = new stdClass();
        $portrequest->numbers->$phone_number = new stdClass();
        $portrequest->save();

        $this->assertTrue((strlen($portrequest->getId()) > 0));
        return $portrequest->getId();
    }

    /**
     * @test
     * @depends testCreatePortRequest
     */
    public function testFetchPortRequest($portrequest_id) {
        $portrequest = $this->getSDK()->Account()->PortRequest($portrequest_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\PortRequest", $portrequest);
        $this->assertTrue((strlen($portrequest->getId()) > 0));
        $this->assertEquals($portrequest->getId(), $portrequest_id);

        return $portrequest;
    }

    /**
     * @test
     * @depends testFetchPortRequest
     */
    public function testUpdatePortRequest($portrequest) {
        $portrequest_id = $portrequest->getId();
        $current_name = $portrequest->name;
        $new_name = "SDK Update First " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $portrequest->name = $new_name;
        $portrequest->save();

        // Make sure we didn't create a new portrequest
        $this->assertEquals($portrequest->getId(), $portrequest_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($portrequest->name, $new_name);

        // Fetch it from the database again to make sure
        $portrequest->fetch();
        $this->assertEquals($portrequest->name, $new_name);
    }

    /**
     * @test
     * @depends testFetchPortRequest
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildPortRequest($portrequest) {
        $portrequest_id = $portrequest->getId();

        $this->assertTrue((strlen($portrequest->getId()) > 0));

        $portrequest->remove();

        $this->assertTrue((strlen($portrequest->getId()) == 0));
    }
}
