<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class CdrTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testGetCdrs() {
        $cdrs = $this->getSDK()->Account()->Cdrs()->fetch();
        $this->assertInstanceOf("\\Kazoo\\Api\\Collection\\Cdrs", $cdrs);
        if (count($cdrs) > 0) {
            $this->additonalTest($cdrs);
        }
    }

    public function additonalTest($cdrs) {
        $inbound_filter = array('filter_call_direction' => 'inbound');
        $inbound_cdrs = $this->getSDK()->Account()->Cdrs($inbound_filter);

        $outbound_filter = array('filter_call_direction' => 'outbound');
        $outbound_cdrs = $this->getSDK()->Account()->Cdrs($outbound_filter);

        $this->assertTrue((count($inbound_cdrs) + count($outbound_cdrs)) == count($cdrs));

        $cdr = current($cdrs)->fetch();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Cdr", $cdr);
    }
}
