<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Directory;
use Kazoo\Api\Data\Entity\User;
use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\RuntimeException;

/**
 * @group functional
 */
class DirectoryTest extends \PHPUnit_Framework_TestCase {

    protected $client;

    public function setUp() {

        $username = 'bwann';
        $password = '12341234';
        $sipRealm = 'sip.benwann.com';
        $options = array();
        $options["base_url"] = "http://192.168.56.111:8000";
        $this->test_user_id = "985db99c5db1b23b6273183c18462616";
        $this->test_callflow_id = "6c6d232a8a81a7d23a565886e33825c0";

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
    public function testCreateEmptyDirectory() {

        try {
            $directory = $this->client->accounts()->directories()->new();
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Directory", $directory);
            return $directory;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateEmptyDirectory
     */
    public function testCreateDirectory($directory) {

        try {

            $user = $this->client->accounts()->users()->retrieve($this->test_user_id);
            $callflow = $this->client->accounts()->callflows()->retrieve($this->test_callflow_id);

            $num = rand(1, 10000);
            $directory->name = "Test Directory #" . $num;
            $directory->save();

            $user->addDirectoryEntry($directory->id, $callflow->id);
            $user->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Directory", $directory);
            $this->assertTrue((strlen($directory->id) > 0));

            return $directory->id;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testCreateDirectory
     */
    public function testRetrieveDirectory($directory_id) {

        try {

            $directory = $this->client->accounts()->directories()->retrieve($directory_id);
            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Directory", $directory);
            $this->assertTrue((strlen($directory->id) > 0));
            return $directory;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testRetrieveDirectory
     */
    public function testUpdateDirectory($directory) {

        try {
            $directory->name = "Updated: " . $directory->name;
            $directory->save();

            $user = $this->client->accounts()->users()->retrieve($this->test_user_id);

            //Remove user from directory
            $user->removeDirectoryEntry($directory->id);
            $user->save();

            $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Directory", $directory);
            $this->assertTrue((strlen($directory->id) > 0));

            return $directory;
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

    /**
     * @test
     * @depends testUpdateDirectory
     */
    public function testRetrieveAllAndUpdateOne($search_directory) {

        try {

            $directories = $this->client->accounts()->directories()->retrieve();
            foreach ($directories as $directory) {
                if ($directory->id == $search_directory->id) {
                    $search_directory->name = "Updated: " . $search_directory->name;
                    $search_directory->save();
                }
            }
            $this->assertGreaterThan(0, count($directories));
            return $search_directory;
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
    public function testDeleteDirectory($directory) {

        try {

            $user_entries = $directory->users;

            foreach ($user_entries as $user_entry) {
                $user = $this->client->accounts()->users()->retrieve($user_entry->user_id);
                $user->removeDirectoryEntry($directory->id);
                $user->save();
            }

            $directory->delete();

            $this->assertTrue(true);    //TODO, figure out assertion for successful deletion
        } catch (RuntimeException $e) {
            $this->markTestSkipped("Runtime Exception: " . $e->getMessage());
        } catch (Exception $e) {
            $this->markTestSkipped("Exception: " . $e->getMessage());
        }
    }

}
