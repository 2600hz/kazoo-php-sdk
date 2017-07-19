<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class MediaTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateMedia() {
        $media = $this->getSDK()->Account()->Media();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Media", $media);
        $this->assertTrue((strlen($media->getId()) == 0));

        $media->name = "SDK Create Test " . rand(100, 1000);
        $media->save();

        $this->assertTrue((strlen($media->getId()) > 0));
        return $media->getId();
    }

    /**
     * @test
     * @depends testCreateMedia
     */
    public function testFetchMedia($media_id) {
        $media = $this->getSDK()->Account()->Media($media_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Media", $media);
        $this->assertTrue((strlen($media->getId()) > 0));
        $this->assertEquals($media->getId(), $media_id);

        return $media;
    }

    /**
     * @test
     * @depends testFetchMedia
     */
    public function testUpdateMedia($media) {
        $media_id = $media->getId();
        $current_name = $media->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $media->name = $new_name;
        $media->save();

        // Make sure we didn't create a new media
        $this->assertEquals($media->getId(), $media_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($media->name, $new_name);

        // Fetch it from the database again to make sure
        $media->fetch();
        $this->assertEquals($media->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateMedia
     */
    public function testListingMedias($media_id) {
        $medias = $this->getSDK()->Account()->Medias();

        $media = null;
        foreach($medias as $element) {
            if ($element->id == $media_id) {
                $media = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Media", $media);
        $this->assertTrue((strlen($media->getId()) > 0));
        $this->assertEquals($media->getId(), $media_id);

        return $media->name;
    }

    /**
     * @test
     * @depends testCreateMedia
     * @depends testListingMedias
     */
    public function testFilteredListingMedias($media_id, $media_name) {
        $filter = array('filter_name' => $media_name);
        $medias = $this->getSDK()->Account()->Medias($filter);

        $this->assertTrue(count($medias) == 1);

        $element = $medias->current();

        $this->assertTrue((strlen($element->id) > 0));
        $this->assertEquals($element->id, $media_id);

        $filter = array('filter_name' => 'no-such-media');
        $medias = $this->getSDK()->Account()->Medias($filter);

        $this->assertTrue(count($medias) == 0);
    }

    /**
     * @test
     * @depends testFetchMedia
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildMedia($media) {
        $media_id = $media->getId();

        $this->assertTrue((strlen($media->getId()) > 0));

        $media->remove();

        $this->assertTrue((strlen($media->getId()) == 0));
    }

}
