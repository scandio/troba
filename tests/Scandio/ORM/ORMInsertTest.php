<?php

class ORMInsertTest extends PHPUnit_Framework_TestCase
{
    public function testInsertDefault()
    {
        $c = new AnotherCompany();
        $c->name = 'A Company from testInsert()';
        $c->remark = 'A remark for A Company from testInsert()';
        \Scandio\ORM\ORM::insert($c);
        $this->assertEquals(CNT_COMPANY + 1, $c->id);

        $c = new Company();
        $c->name = 'A Company from testInsert()';
        $c->remark = 'A remark for A Company from testInsert()';
        \Scandio\ORM\ORM::insert($c);
        $this->assertEquals(CNT_COMPANY + 2, $c->id);

        $p = new Project();
        $p->id = $c->id . '_PROJECT';
        $p->companyId = $c->id;
        $p->name = 'A project with the id ' . $p->id;
        $p->value = 1234.56;
        \Scandio\ORM\ORM::insert($p);
        $this->assertEquals($p->id, \Scandio\ORM\ORM::query([
            'entity' => new Project(),
            'query' => 'id = ?',
            'params' => $p->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->id);

        $pa = new ProjectActivity();
        $pa->id = 999;
        $pa->projectId = $p->id;
        $pa->name = 'Activity for ' . $p->id . ' with ' . $pa->id;
        \Scandio\ORM\ORM::insert($pa);
        $this->assertEquals($pa->id, \Scandio\ORM\ORM::query([
            'entity' => new ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->id);
    }

    public function testInsertDefaultWithNamespace()
    {
        $c = new Bootstrap\AnotherCompany();
        $c->name = 'A Company from testInsert()';
        $c->remark = 'A remark for A Company from testInsert()';
        \Scandio\ORM\ORM::insert($c);
        $this->assertEquals(CNT_COMPANY + 3, $c->id);

        $c = new Bootstrap\Company();
        $c->name = 'A Company from testInsert()';
        $c->remark = 'A remark for A Company from testInsert()';
        \Scandio\ORM\ORM::insert($c);
        $this->assertEquals(CNT_COMPANY + 4, $c->id);

        $p = new Bootstrap\Project();
        $p->id = $c->id . '_PROJECT';
        $p->companyId = $c->id;
        $p->name = 'A project with the id ' . $p->id;
        $p->value = 1234.56;
        \Scandio\ORM\ORM::insert($p);
        $this->assertEquals($p->id, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'id = ?',
            'params' => $p->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->id);

        $pa = new Bootstrap\ProjectActivity();
        $pa->id = 999;
        $pa->projectId = $p->id;
        $pa->name = 'Activity for ' . $p->id . ' with ' . $pa->id;
        \Scandio\ORM\ORM::insert($pa);
        $this->assertEquals($pa->id, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->id);
    }
    public function testInsertClassic()
    {
        \Scandio\ORM\ORM::activateConnection('second_db');

        $c = new Company();
        $c->name = 'A Company from testInsert()';
        $c->remark = 'A remark for A Company from testInsert()';
        \Scandio\ORM\ORM::insert($c);
        $this->assertEquals(CNT_COMPANY + 1, $c->id);

        $p = new Project();
        $p->id = $c->id . '_PROJECT';
        $p->companyId = $c->id;
        $p->name = 'A project with the id ' . $p->id;
        $p->value = 1234.56;
        \Scandio\ORM\ORM::insert($p);
        $this->assertEquals($p->id, \Scandio\ORM\ORM::query([
            'entity' => new Project(),
            'query' => 'id = ?',
            'params' => $p->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->id);

        $pa = new ProjectActivity();
        $pa->id = 999;
        $pa->projectId = $p->id;
        $pa->name = 'Activity for ' . $p->id . ' with ' . $pa->id;
        \Scandio\ORM\ORM::insert($pa);
        $this->assertEquals($pa->id, \Scandio\ORM\ORM::query([
            'entity' => new ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->id);

        \Scandio\ORM\ORM::activateConnection();
    }

    public function testInsertClassicWithNamespace()
    {
        \Scandio\ORM\ORM::activateConnection('second_db');

        $c = new Bootstrap\Company();
        $c->name = 'A Company from testInsert()';
        $c->remark = 'A remark for A Company from testInsert()';
        \Scandio\ORM\ORM::insert($c);
        $this->assertEquals(CNT_COMPANY + 2, $c->id);

        $p = new Bootstrap\Project();
        $p->id = $c->id . '_PROJECT';
        $p->companyId = $c->id;
        $p->name = 'A project with the id ' . $p->id;
        $p->value = 1234.56;
        \Scandio\ORM\ORM::insert($p);
        $this->assertEquals($p->id, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'id = ?',
            'params' => $p->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->id);

        $pa = new Bootstrap\ProjectActivity();
        $pa->id = 999;
        $pa->projectId = $p->id;
        $pa->name = 'Activity for ' . $p->id . ' with ' . $pa->id;
        \Scandio\ORM\ORM::insert($pa);
        $this->assertEquals($pa->id, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->id);

        \Scandio\ORM\ORM::activateConnection();
    }

    public function testInsertError()
    {
        $c = new Company();
        try {
            \Scandio\ORM\ORM::insert($c);
            $r = true;
        } catch (\Scandio\ORM\ORMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $c = new Bootstrap\Company();
        try {
            \Scandio\ORM\ORM::insert($c);
            $r = true;
        } catch (\Scandio\ORM\ORMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $c->name = null;
        $c->remark = 'no no not good';
        try {
            \Scandio\ORM\ORM::insert($c);
            $r = true;
        } catch (\Scandio\ORM\ORMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $p = new Project();
        try {
            \Scandio\ORM\ORM::insert($p);
            $r = true;
        } catch (\Scandio\ORM\ORMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $p->id = null;
        $p->name = 'not good as I said';
        $p->value = 999;
        try {
            \Scandio\ORM\ORM::insert($p);
            $r = true;
        } catch (\Scandio\ORM\ORMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $p->companyId = 1;
        $p->name = 'not good as I said';
        $p->value = 9999;
        try {
            \Scandio\ORM\ORM::insert($p);
            $r = true;
        } catch (\Scandio\ORM\ORMException $e) {
            $r = false;
        }
        $this->assertFalse($r);
    }
}
