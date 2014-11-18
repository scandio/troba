<?php

use troba\EQM\DefaultConventionHandler;
use troba\EQM\ClassicConventionHandler;
use troba\EQM\PrefixedConventionHandler;

class ConventionHandlerTest extends PHPUnit_Framework_TestCase {

    public function testDefault() {
        $this->assertEquals('MyClass MyClass',
            (new DefaultConventionHandler())->tableName('MyClass'));
        $this->assertEquals('table_name MyClass',
            (new DefaultConventionHandler())->tableName('MyClass', 'table_name'));
    }

    public function testClassic() {
        $this->assertEquals('mytable Mytable',
            (new ClassicConventionHandler())->tableName('Mytable'));
        $this->assertEquals('my_class MyClass',
            (new ClassicConventionHandler())->tableName('MyClass'));
        $this->assertEquals('my_class_for_more MyClassForMore',
            (new ClassicConventionHandler())->tableName('MyClassForMore'));
        $this->assertEquals('table_name MyClass',
            (new ClassicConventionHandler())->tableName('MyClass', 'table_name'));
    }

    public function testPrefixed() {
        $this->assertEquals('orm_MyClass MyClass',
            (new PrefixedConventionHandler('orm_', new DefaultConventionHandler()))->tableName('MyClass'));
        $this->assertEquals('orm_my_class MyClass',
            (new PrefixedConventionHandler('orm_', new ClassicConventionHandler()))->tableName('MyClass'));
        $this->assertEquals('orm_my_class_for_more MyClassForMore',
            (new PrefixedConventionHandler('orm_', new ClassicConventionHandler()))->tableName('MyClassForMore'));
    }
}