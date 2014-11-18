<?php

namespace Bootstrap;

use troba\EQM\EQM;

class ResultSetArrayTest extends \PHPUnit_Framework_TestCase {

    public function testResult() {
        $result = EQM::query(Company::class)->result();
        $count = $result->count();
        $all = $result->all();
        $this->assertEquals($all, $all->all());
        $this->assertInstanceOf(Company::class, $all->one());
        $this->assertEquals($all->count(), $count);
    }
}
 