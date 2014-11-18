<?php

use troba\EQM\EQM;
use troba\EQM\EQMException;

class EQMInsertTest extends PHPUnit_Framework_TestCase {

    public function testInsertDefault() {
        $c = new AnotherCompany();
        $c->name = 'A Company from testInsert()';
        $c->remark = 'A remark for A Company from testInsert()';
        EQM::insert($c);
        $this->assertEquals(CNT_COMPANY + 1, $c->id);

        $c = new Company();
        $c->name = 'A Company from testInsert()';
        $c->remark = 'A remark for A Company from testInsert()';
        EQM::insert($c);
        $this->assertEquals(CNT_COMPANY + 2, $c->id);

        $p = new Project();
        $p->id = $c->id . '_PROJECT';
        $p->companyId = $c->id;
        $p->name = 'A project with the id ' . $p->id;
        $p->value = 1234.56;
        EQM::insert($p);
        $this->assertEquals($p->id, EQM::queryByArray([
            'entity' => Project::class,
            'query' => 'id = ?',
            'params' => $p->id
        ])->one()->id);

        $pa = new ProjectActivity();
        $pa->id = 999;
        $pa->projectId = $p->id;
        $pa->name = 'Activity for ' . $p->id . ' with ' . $pa->id;
        EQM::insert($pa);
        $this->assertEquals($pa->id, EQM::queryByArray([
            'entity' => ProjectActivity::class,
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ])->one()->id);
    }

    public function testInsertDefaultWithNamespace() {
        $c = new Bootstrap\AnotherCompany();
        $c->name = 'A Company from testInsert()';
        $c->remark = 'A remark for A Company from testInsert()';
        EQM::insert($c);
        $this->assertEquals(CNT_COMPANY + 3, $c->id);

        $c = new Bootstrap\Company();
        $c->name = 'A Company from testInsert()';
        $c->remark = 'A remark for A Company from testInsert()';
        EQM::insert($c);
        $this->assertEquals(CNT_COMPANY + 4, $c->id);

        $p = new Bootstrap\Project();
        $p->id = $c->id . '_PROJECT';
        $p->companyId = $c->id;
        $p->name = 'A project with the id ' . $p->id;
        $p->value = 1234.56;
        EQM::insert($p);
        $this->assertEquals($p->id, EQM::queryByArray([
            'entity' => Bootstrap\Project::class,
            'query' => 'id = ?',
            'params' => $p->id
        ])->one()->id);

        $pa = new Bootstrap\ProjectActivity();
        $pa->id = 999;
        $pa->projectId = $p->id;
        $pa->name = 'Activity for ' . $p->id . ' with ' . $pa->id;
        EQM::insert($pa);
        $this->assertEquals($pa->id, EQM::queryByArray([
            'entity' => Bootstrap\ProjectActivity::class,
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ])->one()->id);
    }

    public function testInsertClassic() {
        EQM::activateConnection('second_db');

        $c = new Company();
        $c->name = 'A Company from testInsert()';
        $c->remark = 'A remark for A Company from testInsert()';
        EQM::insert($c);
        $this->assertEquals(CNT_COMPANY + 1, $c->id);

        $p = new Project();
        $p->id = $c->id . '_PROJECT';
        $p->companyId = $c->id;
        $p->name = 'A project with the id ' . $p->id;
        $p->value = 1234.56;
        EQM::insert($p);
        $this->assertEquals($p->id, EQM::queryByArray([
            'entity' => Project::class,
            'query' => 'id = ?',
            'params' => $p->id
        ])->one()->id);

        $pa = new ProjectActivity();
        $pa->id = 999;
        $pa->projectId = $p->id;
        $pa->name = 'Activity for ' . $p->id . ' with ' . $pa->id;
        EQM::insert($pa);
        $this->assertEquals($pa->id, EQM::queryByArray([
            'entity' => ProjectActivity::class,
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ])->one()->id);

        EQM::activateConnection();
    }

    public function testInsertClassicWithNamespace() {
        EQM::activateConnection('second_db');

        $c = new Bootstrap\Company();
        $c->name = 'A Company from testInsert()';
        $c->remark = 'A remark for A Company from testInsert()';
        EQM::insert($c);
        $this->assertEquals(CNT_COMPANY + 2, $c->id);

        $p = new Bootstrap\Project();
        $p->id = $c->id . '_PROJECT';
        $p->companyId = $c->id;
        $p->name = 'A project with the id ' . $p->id;
        $p->value = 1234.56;
        EQM::insert($p);
        $this->assertEquals($p->id, EQM::queryByArray([
            'entity' => Bootstrap\Project::class,
            'query' => 'id = ?',
            'params' => $p->id
        ])->one()->id);

        $pa = new Bootstrap\ProjectActivity();
        $pa->id = 999;
        $pa->projectId = $p->id;
        $pa->name = 'Activity for ' . $p->id . ' with ' . $pa->id;
        EQM::insert($pa);
        $this->assertEquals($pa->id, EQM::queryByArray([
            'entity' => Bootstrap\ProjectActivity::class,
            'query' => 'id = ? AND projectId = ?',
            'params' => [$pa->id, $p->id]
        ])->one()->id);

        EQM::activateConnection();
    }

    public function testInsertError() {
        $c = new Company();
        try {
            EQM::insert($c);
            $r = true;
        } catch (EQMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $c = new Bootstrap\Company();
        try {
            EQM::insert($c);
            $r = true;
        } catch (EQMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $c->name = null;
        $c->remark = 'no no not good';
        try {
            EQM::insert($c);
            $r = true;
        } catch (EQMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $p = new Project();
        try {
            EQM::insert($p);
            $r = true;
        } catch (EQMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $p->id = null;
        $p->name = 'not good as I said';
        $p->value = 999;
        try {
            EQM::insert($p);
            $r = true;
        } catch (EQMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $p->companyId = 1;
        $p->name = 'not good as I said';
        $p->value = 9999;
        try {
            EQM::insert($p);
            $r = true;
        } catch (EQMException $e) {
            $r = false;
        }
        $this->assertFalse($r);
    }
}
