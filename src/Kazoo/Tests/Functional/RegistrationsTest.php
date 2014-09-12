<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */

class RegistrationsTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testGetRegistrations(){
        $registrations = $this->getSDK()->Account()->Registrations();
        $this->assertInstanceOf("\\Kazoo\\Api\\Collection\\Registrations", $registrations);

    }

}