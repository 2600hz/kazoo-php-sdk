<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class DirectoryTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testCreateDirectory() {
        $directory = $this->getSDK()->Account()->Directory();

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Directory", $directory);
        $this->assertTrue((strlen($directory->getId()) == 0));

        $directory->name = "SDK Create Test " . rand(100, 1000);
        $directory->save();

        $this->assertTrue((strlen($directory->getId()) > 0));
        return $directory->getId();
    }

    /**
     * @test
     * @depends testCreateDirectory
     */
    public function testFetchDirectory($directory_id) {
        $directory = $this->getSDK()->Account()->Directory($directory_id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Directory", $directory);
        $this->assertTrue((strlen($directory->getId()) > 0));
        $this->assertEquals($directory->getId(), $directory_id);

        return $directory;
    }

    /**
     * @test
     * @depends testFetchDirectory
     */
    public function testUpdateDirectory($directory) {
        $directory_id = $directory->getId();
        $current_name = $directory->name;
        $new_name = "SDK Update Test " . rand(100, 1000);

        // Ensure our test actually update the name...
        $this->assertNotEquals($current_name, $new_name);

        $directory->name = $new_name;
        $directory->save();

        // Make sure we didn't create a new directory
        $this->assertEquals($directory->getId(), $directory_id);

        // The local copy is updated with the result of the
        // API request
        $this->assertEquals($directory->name, $new_name);

        // Fetch it from the database again to make sure
        $directory->fetch();
        $this->assertEquals($directory->name, $new_name);
    }

    /**
     * @test
     * @depends testCreateDirectory
     */
    public function testListingDirectory($directory_id) {
        $directories = $this->getSDK()->Account()->Directories();

        $directory = null;
        foreach($directories as $element) {
            if ($element->id == $directory_id) {
                $directory = $element->fetch();
                break;
            }
        }

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\Directory", $directory);
        $this->assertTrue((strlen($directory->getId()) > 0));
        $this->assertEquals($directory->getId(), $directory_id);

        return $directory->name;
    }

    /**
     * @test
     * @depends testFetchDirectory
     * @expectedExceptionDisabled \Kazoo\HttpClient\Exception\NotFound
     */
    public function testRemoveChildDirectory($directory) {
        $directory_id = $directory->getId();

        $this->assertTrue((strlen($directory->getId()) > 0));
        $directory->remove();
        $this->assertTrue((strlen($directory->getId()) == 0));
    }

}
