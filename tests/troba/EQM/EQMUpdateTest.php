<?php

class EQMUpdateTest extends PHPUnit_Framework_TestCase
{
    public function testUpdateDefault()
    {
        $c = \troba\EQM\EQM::query([
            'entity' => new AnotherCompany(),
            'query' => 'id = ?',
            'params' => CNT_COMPANY + 1
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $c->remark = $c->remark . ' no from testUpdate(AnotherCompany)';
        \troba\EQM\EQM::update($c);
        $this->assertEquals($c->remark, \troba\EQM\EQM::query([
            'entity' => new AnotherCompany(),
            'query' => 'id = ?',
            'params' => $c->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->remark);

        $c = \troba\EQM\EQM::query([
            'entity' => new Company(),
            'query' => 'id = ?',
            'params' => CNT_COMPANY + 2
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $c->remark = $c->remark . ' no from testUpdate(Company)';
        \troba\EQM\EQM::update($c);
        $this->assertEquals($c->remark, \troba\EQM\EQM::query([
            'entity' => new Company(),
            'query' => 'id = ?',
            'params' => $c->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->remark);

        $p = \troba\EQM\EQM::query([
            'entity' => new Project(),
            'query' => 'id = ?',
            'params' => $c->id . '_PROJECT'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $p->name = $p->name . ' from testUpdate(Project)';
        \troba\EQM\EQM::update($p);
        $this->assertEquals($p->name, \troba\EQM\EQM::query([
            'entity' => new Project(),
            'query' => 'id = ?',
            'params' => $p->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->name);

        $pa = \troba\EQM\EQM::query([
            'entity' => new ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [999, $p->id]
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $pa->name = $pa->name . ' from testUpdate(ProjectActivity)';
        \troba\EQM\EQM::update($pa);
        $this->assertEquals($pa->name, \troba\EQM\EQM::query([
            'entity' => new ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->name);
    }

    public function testUpdateDefaultWithNameSpace()
    {
        $c = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\AnotherCompany(),
            'query' => 'id = ?',
            'params' => CNT_COMPANY + 3
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $c->remark = $c->remark . ' no from testUpdate(AnotherCompany)';
        \troba\EQM\EQM::update($c);
        $this->assertEquals($c->remark, \troba\EQM\EQM::query([
            'entity' => new Bootstrap\AnotherCompany(),
            'query' => 'id = ?',
            'params' => $c->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->remark);

        $c = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Company(),
            'query' => 'id = ?',
            'params' => CNT_COMPANY + 4
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $c->remark = $c->remark . ' no from testUpdate(Company)';
        \troba\EQM\EQM::update($c);
        $this->assertEquals($c->remark, \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Company(),
            'query' => 'id = ?',
            'params' => $c->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->remark);

        $p = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'id = ?',
            'params' => $c->id . '_PROJECT'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $p->name = $p->name . ' from testUpdate(Project)';
        \troba\EQM\EQM::update($p);
        $this->assertEquals($p->name, \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'id = ?',
            'params' => $p->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->name);

        $pa = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [999, $p->id]
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $pa->name = $pa->name . ' from testUpdate(ProjectActivity)';
        \troba\EQM\EQM::update($pa);
        $this->assertEquals($pa->name, \troba\EQM\EQM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->name);
    }

    public function testUpdateClassic()
    {
        \troba\EQM\EQM::activateConnection('second_db');

        $c = \troba\EQM\EQM::query([
            'entity' => new Company(),
            'query' => 'id = ?',
            'params' => CNT_COMPANY + 1
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $c->remark = $c->remark . ' no from testUpdate(Company)';
        \troba\EQM\EQM::update($c);
        $this->assertEquals($c->remark, \troba\EQM\EQM::query([
            'entity' => new Company(),
            'query' => 'id = ?',
            'params' => $c->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->remark);

        $p = \troba\EQM\EQM::query([
            'entity' => new Project(),
            'query' => 'id = ?',
            'params' => $c->id . '_PROJECT'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $p->name = $p->name . ' from testUpdate(Project)';
        \troba\EQM\EQM::update($p);
        $this->assertEquals($p->name, \troba\EQM\EQM::query([
            'entity' => new Project(),
            'query' => 'id = ?',
            'params' => $p->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->name);

        $pa = \troba\EQM\EQM::query([
            'entity' => new ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [999, $p->id]
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $pa->name = $pa->name . ' from testUpdate(ProjectActivity)';
        \troba\EQM\EQM::update($pa);
        $this->assertEquals($pa->name, \troba\EQM\EQM::query([
            'entity' => new ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->name);

        \troba\EQM\EQM::activateConnection('second_db');
    }

    public function testUpdateClassicWithNamespace()
    {
        \troba\EQM\EQM::activateConnection('second_db');

        $c = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Company(),
            'query' => 'id = ?',
            'params' => CNT_COMPANY + 1
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $c->remark = $c->remark . ' no from testUpdate(Company)';
        \troba\EQM\EQM::update($c);
        $this->assertEquals($c->remark, \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Company(),
            'query' => 'id = ?',
            'params' => $c->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->remark);

        $p = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'id = ?',
            'params' => $c->id . '_PROJECT'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $p->name = $p->name . ' from testUpdate(Project)';
        \troba\EQM\EQM::update($p);
        $this->assertEquals($p->name, \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'id = ?',
            'params' => $p->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->name);

        $pa = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [999, $p->id]
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one();
        $pa->name = $pa->name . ' from testUpdate(ProjectActivity)';
        \troba\EQM\EQM::update($pa);
        $this->assertEquals($pa->name, \troba\EQM\EQM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->name);

        \troba\EQM\EQM::activateConnection();
    }

    public function testUpdateError()
    {
        $c = new Company();
        try {
            \troba\EQM\EQM::update($c);
            $r = true;
        } catch (\troba\EQM\EQMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

    }

}
