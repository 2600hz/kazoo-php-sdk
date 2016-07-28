<?php

namespace Kazoo\Tests\Special;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class AppsLinkTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testFetchLegitAuthToken() {
        $apps_link = $this->getSDK()->Account()->AppsLink();
        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\AppsLink", $apps_link);
        $apps_link->fetch();
        $this->assertTrue(is_object($apps_link->auth_token));
        $this->assertTrue(is_object($apps_link->account));
    }
}
