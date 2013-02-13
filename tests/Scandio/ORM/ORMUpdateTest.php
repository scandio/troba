<?php

class ORMUpdateTest extends PHPUnit_Framework_TestCase
{
    public function testUpdateDefault()
    {
        $c = \Scandio\ORM\ORM::query([
            'entity' => new AnotherCompany(),
            'query' => 'id = ?',
            'params' => CNT_COMPANY + 1
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $c->remark = $c->remark . ' no from testUpdate(AnotherCompany)';
        \Scandio\ORM\ORM::update($c);
        $this->assertEquals($c->remark, \Scandio\ORM\ORM::query([
            'entity' => new AnotherCompany(),
            'query' => 'id = ?',
            'params' => $c->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->remark);

        $c = \Scandio\ORM\ORM::query([
            'entity' => new Company(),
            'query' => 'id = ?',
            'params' => CNT_COMPANY + 2
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $c->remark = $c->remark . ' no from testUpdate(Company)';
        \Scandio\ORM\ORM::update($c);
        $this->assertEquals($c->remark, \Scandio\ORM\ORM::query([
            'entity' => new Company(),
            'query' => 'id = ?',
            'params' => $c->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->remark);

        $p = \Scandio\ORM\ORM::query([
            'entity' => new Project(),
            'query' => 'id = ?',
            'params' => $c->id . '_PROJECT'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $p->name = $p->name . ' from testUpdate(Project)';
        \Scandio\ORM\ORM::update($p);
        $this->assertEquals($p->name, \Scandio\ORM\ORM::query([
            'entity' => new Project(),
            'query' => 'id = ?',
            'params' => $p->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->name);

        $pa = \Scandio\ORM\ORM::query([
            'entity' => new ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [999, $p->id]
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $pa->name = $pa->name . ' from testUpdate(ProjectActivity)';
        \Scandio\ORM\ORM::update($pa);
        $this->assertEquals($pa->name, \Scandio\ORM\ORM::query([
            'entity' => new ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->name);
    }

    public function testUpdateDefaultWithNameSpace()
    {
        $c = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\AnotherCompany(),
            'query' => 'id = ?',
            'params' => CNT_COMPANY + 3
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $c->remark = $c->remark . ' no from testUpdate(AnotherCompany)';
        \Scandio\ORM\ORM::update($c);
        $this->assertEquals($c->remark, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\AnotherCompany(),
            'query' => 'id = ?',
            'params' => $c->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->remark);

        $c = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Company(),
            'query' => 'id = ?',
            'params' => CNT_COMPANY + 4
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $c->remark = $c->remark . ' no from testUpdate(Company)';
        \Scandio\ORM\ORM::update($c);
        $this->assertEquals($c->remark, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Company(),
            'query' => 'id = ?',
            'params' => $c->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->remark);

        $p = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'id = ?',
            'params' => $c->id . '_PROJECT'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $p->name = $p->name . ' from testUpdate(Project)';
        \Scandio\ORM\ORM::update($p);
        $this->assertEquals($p->name, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'id = ?',
            'params' => $p->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->name);

        $pa = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [999, $p->id]
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $pa->name = $pa->name . ' from testUpdate(ProjectActivity)';
        \Scandio\ORM\ORM::update($pa);
        $this->assertEquals($pa->name, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->name);
    }

    public function testUpdateClassic()
    {
        \Scandio\ORM\ORM::activateConnection('second_db');

        $c = \Scandio\ORM\ORM::query([
            'entity' => new Company(),
            'query' => 'id = ?',
            'params' => CNT_COMPANY + 1
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $c->remark = $c->remark . ' no from testUpdate(Company)';
        \Scandio\ORM\ORM::update($c);
        $this->assertEquals($c->remark, \Scandio\ORM\ORM::query([
            'entity' => new Company(),
            'query' => 'id = ?',
            'params' => $c->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->remark);

        $p = \Scandio\ORM\ORM::query([
            'entity' => new Project(),
            'query' => 'id = ?',
            'params' => $c->id . '_PROJECT'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $p->name = $p->name . ' from testUpdate(Project)';
        \Scandio\ORM\ORM::update($p);
        $this->assertEquals($p->name, \Scandio\ORM\ORM::query([
            'entity' => new Project(),
            'query' => 'id = ?',
            'params' => $p->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->name);

        $pa = \Scandio\ORM\ORM::query([
            'entity' => new ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [999, $p->id]
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $pa->name = $pa->name . ' from testUpdate(ProjectActivity)';
        \Scandio\ORM\ORM::update($pa);
        $this->assertEquals($pa->name, \Scandio\ORM\ORM::query([
            'entity' => new ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->name);

        \Scandio\ORM\ORM::activateConnection('second_db');
    }

    public function testUpdateClassicWithNamespace()
    {
        \Scandio\ORM\ORM::activateConnection('second_db');

        $c = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Company(),
            'query' => 'id = ?',
            'params' => CNT_COMPANY + 1
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $c->remark = $c->remark . ' no from testUpdate(Company)';
        \Scandio\ORM\ORM::update($c);
        $this->assertEquals($c->remark, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Company(),
            'query' => 'id = ?',
            'params' => $c->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->remark);

        $p = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'id = ?',
            'params' => $c->id . '_PROJECT'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $p->name = $p->name . ' from testUpdate(Project)';
        \Scandio\ORM\ORM::update($p);
        $this->assertEquals($p->name, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'id = ?',
            'params' => $p->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->name);

        $pa = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [999, $p->id]
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one();
        $pa->name = $pa->name . ' from testUpdate(ProjectActivity)';
        \Scandio\ORM\ORM::update($pa);
        $this->assertEquals($pa->name, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->name);

        \Scandio\ORM\ORM::activateConnection();
    }

    public function testUpdateError()
    {
        $c = new Company();
        try {
            \Scandio\ORM\ORM::update($c);
            $r = true;
        } catch (\Scandio\ORM\ORMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

    }

}
