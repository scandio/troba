<?php

namespace Bootstrap;

use troba\EQM\ClassicConventionHandler;
use troba\EQM\EQM;
use troba\EQM\EQMException;
use troba\EQM\MySqlBuilder;

class EQMInitializeTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        try {
            EQM::initialize(null, [], 'test0');
        } catch (EQMException $e) {
            $this->assertEquals($e->getMessage(), 'The given paramter is not a valid PDO connection object');
        }
        EQM::initialize(new \PDO('mysql:host=localhost;dbname=orm_test', 'root', 'root'), [], 'test1');
        $this->assertTrue(EQM::isInitialized('test1'));
        EQM::initialize(new \PDO('mysql:host=localhost;dbname=orm_test', 'root', 'root'), [
            EQM::CONVENTION_HANDLER => new ClassicConventionHandler()
        ], 'test2');
        $this->assertTrue(EQM::isInitialized('test2'));
        EQM::initialize(new \PDO('mysql:host=localhost;dbname=orm_test', 'root', 'root'), [
            EQM::SQL_BUILDER => new MySqlBuilder()
        ], 'test3');
        $this->assertTrue(EQM::isInitialized('test3'));
    }
}
 