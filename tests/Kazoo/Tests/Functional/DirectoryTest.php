<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Resources\Directory;

/**
 * @group functional
 */
class DirectoryTest extends TestCase {

    /**
     * @test
     */
    public function testEmptyShell() {
        $directory = $this->client->api('accounts')->directories()->new();
        $this->assertObjectHasAttribute('sort_by', $directory);
        $this->assertObjectHasAttribute('min_dtmf', $directory);
        $this->assertObjectHasAttribute('max_dtmf', $directory);
        $this->assertObjectHasAttribute('confirm_match', $directory);
        $this->assertInstanceOf("Kazoo\\Api\\Resources\\Directory", $directory);
    }

}