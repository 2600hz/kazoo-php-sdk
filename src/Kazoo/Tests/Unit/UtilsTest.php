<?php

namespace Kazoo\Tests\Unit;

use \Kazoo\Common\Utils;
use \Kazoo\Tests\Common\UnitTest;

/**
 * @group unit
 */
class UtilsTest extends UnitTest
{
    /**
     * @test
     */
    public function testShortClassName() {
        $className = Utils::shortClassName($this);
        $this->assertEquals($className, "UtilsTest");
    }

    /**
     * @test
     */
    public function testUnderscoreClassName() {
        $className = Utils::underscoreClassName($this);
        $this->assertEquals($className, "utils_test");
    }

    /**
     * @test
     */
    public function testDepluralize() {
        $word = Utils::depluralize("accounts");
        $this->assertEquals($word, "account");

        $word = Utils::depluralize("user");
        $this->assertEquals($word, "user");

        $word = Utils::depluralize("guess");
        $this->assertEquals($word, "guess");

        $word = Utils::depluralize("vmboxes");
        $this->assertEquals($word, "vmbox");
    }

    /**
     * @test
     */
    public function testPluralize() {
        $word = Utils::pluralize("success");
        $this->assertEquals($word, "successes");

        $word = Utils::pluralize("connectivity");
        $this->assertEquals($word, "connectivities");

        $word = Utils::pluralize("account");
        $this->assertEquals($word, "accounts");

        $word = Utils::pluralize("whatdoesthefox");
        $this->assertEquals($word, "whatdoesthefoxes");
    }
}