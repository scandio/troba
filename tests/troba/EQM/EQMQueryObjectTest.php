<?php

use troba\EQM\EQM;

class EQMQueryObjectTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultSimpleQuery()
    {
        $count = EQM::query(['entity' => 'Company'], EQM::QUERY_TYPE_ARRAY)->count();
        $result = EQM::query('Company')->result();
        $this->assertEquals($count, $result->count());
        $resultArray = $result->all();
        $this->assertTrue(is_array($resultArray));
        $this->assertEquals($count, count($resultArray));
        $this->assertEquals('Company', get_class($resultArray[0]));
    }

    public function testDefaultJoinedQuery()
    {
        $result = EQM::query(new Company())
            ->innerJoin(new Project(), 'Company.id = Project.companyId')
            ->where('Project.companyId = ?', 2)
            ->result();
        $this->assertEquals(CNT_PROJECT, $result->count());
    }
}
