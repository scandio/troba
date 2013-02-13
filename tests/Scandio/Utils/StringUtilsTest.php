<?php

use Scandio\Util\StringUtils;

class StringUtilsTest extends PHPUnit_Framework_TestCase {

    function testCamelCase() {
        $this->assertEquals('myString', StringUtils::camelCaseFrom('my-string'));
        $this->assertEquals('myString', StringUtils::camelCaseFrom('my_string', '_'));
        $this->assertEquals('myStringIsCool', StringUtils::camelCaseFrom('my#string#is#cool', '#'));
        $this->assertEquals('myStringIsCool', StringUtils::camelCaseFrom('my_string_is_cool', '_'));
        $this->assertEquals('my-stringIsCool', StringUtils::camelCaseFrom('my-string_is_cool', '_'));

        $this->assertEquals('my-string', StringUtils::camelCaseTo('myString'));
        $this->assertEquals('my-string', StringUtils::camelCaseTo('MyString'));
        $this->assertEquals('my_string', StringUtils::camelCaseTo('myString', '_'));
        $this->assertEquals('my-string-is-cool', StringUtils::camelCaseTo('myStringIsCool'));
        $this->assertEquals('my_string_is_cool', StringUtils::camelCaseTo('myStringIsCool', '_'));
        $this->assertEquals('my string is cool', StringUtils::camelCaseTo('myStringIsCool', ' '));
    }

}
