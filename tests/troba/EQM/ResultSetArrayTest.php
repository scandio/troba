<?php

namespace Bootstrap;

use troba\EQM\EQM;

class ResultSetArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testResult()
    {
        $result = EQM::query(new Company())->result();
        $count = $result->count();
        $all = $result->all();
        $this->assertEquals($all, $all->all());
        $this->assertInstanceOf(get_class(new Company()), $all->one());
        $this->assertEquals($all->count(), $count);
    }
}
 