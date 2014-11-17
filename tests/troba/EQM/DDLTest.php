<?php

namespace Bootstrap;

class Activity
{

}

use troba\EQM\DDL;

class DDLTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        DDL::initialize(
            new \PDO(
                'mysql:host=localhost;dbname=orm_test', 'root', 'root',
                [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"]
            ), [], 'ddl-connection'
        );
        DDL::activateConnection('ddl-connection');
    }

    public function testCreate()
    {
        DDL::create(new Activity(), [
            'id' => [DDL::INTEGER, DDL::AUTO_INCREMENT, DDL::PRIMARY],
            'date' => [DDL::DATE_TIME],
            'note' => [DDL::STRING]
        ]);
        $meta = DDL::tableMeta(new Activity());
        $this->assertTrue($meta->hasAutoIncrement());
        $this->assertArrayHasKey('id', $meta->getColumns());
        $this->assertArrayHasKey('date', $meta->getColumns());
        $this->assertArrayHasKey('note', $meta->getColumns());
        DDL::create('TestTable', [
           'id' => [DDL::INTEGER, DDL::AUTO_INCREMENT, DDL::PRIMARY],
            'uniqueValue' =>[DDL::STRING, DDL::NOT_NULL, DDL::UNIQUE]
        ]);
        $meta = DDL::tableMeta('TestTable');
        $this->assertArrayHasKey('id', $meta->getColumns());
        $this->assertArrayHasKey('uniqueValue', $meta->getColumns());
    }

    public function testAdd()
    {
        DDL::addColumn(new Activity(), 'user', [DDL::STRING]);
        $meta = DDL::tableMeta(new Activity());
        $this->assertArrayHasKey('user', $meta->getColumns());
    }

    public function testDrop()
    {
        DDL::drop(new Activity());
        $meta = DDL::tableMeta(new Activity());
        $this->assertEmpty($meta->getColumns());
        DDL::drop('TestTable');
        $meta = DDL::tableMeta('TestTable');
        $this->assertEmpty($meta->getColumns());
        DDL::activateConnection();
    }
}
 