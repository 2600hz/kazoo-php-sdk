<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Resources\Conference;

/**
 * @group functional
 */
class ConferenceTest extends TestCase {

    /**
     * @test
     */
    public function testEmptyShell() {
        $conference = $this->client->api('accounts')->conferences()->new();
        $this->assertObjectHasAttribute('name', $conference);
        $this->assertObjectHasAttribute('play_welcome', $conference);
        $this->assertObjectHasAttribute('play_entry_tone', $conference);
        $this->assertObjectHasAttribute('member', $conference);
        $this->assertObjectHasAttribute('moderator', $conference);
        $this->assertObjectHasAttribute('conference_numbers', $conference);
        $this->assertObjectHasAttribute('require_moderator', $conference);
        $this->assertObjectHasAttribute('wait_for_moderator', $conference);
        $this->assertObjectHasAttribute('max_members', $conference);
        $this->assertInstanceOf("Kazoo\\Api\\Resources\\Conference", $conference);
    }

}