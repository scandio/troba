<?php

namespace EventTest;

use Bootstrap\Project;
use troba\EQM\EQM;

class Company {

    public function preInsert() {
        $this->name = 'PRE_INS_' . $this->name;
    }

    public function preUpdate() {
        $this->name = 'PRE_UPD_' . $this->name;
    }
}

class ACompany {

    private $__table = 'Company';

    public function postInsert() {
        $p = new Project();
        $p->id = 'PRO_' . $this->id;
        $p->companyId = $this->id;
        EQM::insert($p);
    }

    public function postUpdate() {
        $this->name = 'POST_UPD_' . $this->name;
    }
}

class BCompany {

    private $__table = 'Company';

    public function preDelete() {
        $c = new Company();
        $c->name = 'NAME_DEL';
        EQM::insert($c);
    }

}

class CCompany {

    private $__table = 'Company';

    public function postDelete() {
        $this->remark = 'DELETED';
    }

}

class EQMEventTest extends \PHPUnit_Framework_TestCase {

    public function testPreInsert() {
        $c = new Company();
        $c->name = 'NAME';
        EQM::insert($c);
        $c2 = EQM::queryByPrimary(Company::class, $c->id);
        $this->assertEquals($c2->name, 'PRE_INS_NAME');
        EQM::delete($c2);
    }

    public function testPostInsert() {
        $c = new ACompany();
        $c->name = 'NAME';
        EQM::insert($c);
        $c2 = EQM::queryByPrimary(Company::class, $c->id);
        $this->assertEquals($c2->id, $c->id);
        $p = EQM::queryByPrimary(Project::class, 'PRO_' . $c2->id);
        $this->assertEquals($p->companyId, $c2->id);
        EQM::delete($p);
        EQM::delete($c2);
    }

    public function testPreUpdate() {
        $c = new Company();
        $c->name = 'NAME';
        EQM::insert($c);
        EQM::update($c);
        $c2 = EQM::queryByPrimary(Company::class, $c->id);
        $this->assertEquals($c2->name, 'PRE_UPD_PRE_INS_NAME');
        EQM::delete($c2);
    }

    public function testPostUpdate() {
        $c = new ACompany();
        $c->name = 'NAME';
        EQM::insert($c);
        EQM::update($c);
        $this->assertEquals($c->name, 'POST_UPD_NAME');
        EQM::delete($c);
    }

    public function testPreDelete() {
        $c = new BCompany();
        $c->name = 'NAME';
        EQM::insert($c);
        EQM::delete($c);
        $q = EQM::query(Company::class)->where('name = ?', ['PRE_INS_NAME_DEL'])->all();
        $this->assertEquals($q->count(), 1);
        $this->assertEquals($q[0]->name, 'PRE_INS_NAME_DEL');
    }

    public function testPostDelete() {
        $c = new CCompany();
        $c->name = 'NAME';
        EQM::insert($c);
        EQM::delete($c);
        $this->assertEquals($c->remark, 'DELETED');
    }
}