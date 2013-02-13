<?php

use troba\Util\StringUtils as S;

class StringUtilsTest extends PHPUnit_Framework_TestCase {

    function testCamelCase() {
        $this->assertEquals('myString', S::camelCaseFrom('my-string'));
        $this->assertEquals('myString', S::camelCaseFrom('my_string', '_'));
        $this->assertEquals('myStringIsCool', S::camelCaseFrom('my#string#is#cool', '#'));
        $this->assertEquals('myStringIsCool', S::camelCaseFrom('my_string_is_cool', '_'));
        $this->assertEquals('my-stringIsCool', S::camelCaseFrom('my-string_is_cool', '_'));

        $this->assertEquals('my-string', S::camelCaseTo('myString'));
        $this->assertEquals('my-string', S::camelCaseTo('MyString'));
        $this->assertEquals('my_string', S::camelCaseTo('myString', '_'));
        $this->assertEquals('my-string-is-cool', S::camelCaseTo('myStringIsCool'));
        $this->assertEquals('my_string_is_cool', S::camelCaseTo('myStringIsCool', '_'));
        $this->assertEquals('my string is cool', S::camelCaseTo('myStringIsCool', ' '));
    }

}
