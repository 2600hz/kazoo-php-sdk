<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Media;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class MediaTest extends \PHPUnit_Framework_TestCase {

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
    public function testCreateEmptyMedia() {

        try {
            $media = $this->client->accounts()->media()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Media", $media);

            return $media;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyMedia
     */
    public function testCreateMedia($media) {

        try {
            $num = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
            
            $media->name = "Test Media #" . $num;
            $media->save();
            
            echo $media->getUri() . "\n";
            
            $media->setUploadFilePath(dirname(__FILE__) . "/../assets/greeting.wav");
            $media->upload();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Media", $media);
            $this->assertTrue((strlen($media->id) > 0));

            return $media->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateMedia
     */
    public function testRetrieveMedia($media_id) {

        try {
            $media = $this->client->accounts()->media()->retrieve($media_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Media", $media);
            $this->assertTrue((strlen($media->id) > 0));
            return $media;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveMedia
     */
    public function testUpdateMedia($media) {

        try {
            $media->name = "Updated: " . $media->name;
            $media->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Media", $media);
            $this->assertTrue((strlen($media->id) > 0));

            return $media;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }
    
    /**
     * @test
     * @depends testUpdateMedia
     */
    public function testRetrieveAllAndUpdateOne($search_media) {
        
        try {
            
            $medias = $this->client->accounts()->media()->retrieve();
            foreach($medias as $media){
                if($media->id == $search_media->id){
                    $search_media->name = "Updated: " . $search_media->name;
                    $search_media->save();
                }
            }
            $this->assertGreaterThan(0, count($medias));
            return $search_media;
            
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
    public function testDeleteMedia($media) {

        try {
            $media->delete();
            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
