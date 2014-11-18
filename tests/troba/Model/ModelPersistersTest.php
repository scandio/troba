<?php

namespace ModelPersistersTest;

use troba\EQM\EQMException;
use troba\Model\Finders;
use troba\Model\Persisters;

class Company {

    use Finders;
    use Persisters;
}

class Project {

    use Finders;
    use Persisters;
}

class ProjectActivity {

    use Finders;
    use Persisters;
}

class ModelPersistersTest extends \PHPUnit_Framework_TestCase {

    public function testInsert() {
        $c = new Company();
        $c->name = 'Model Persister Company';
        $c->insert();
        $this->assertNotNull($c->id);
    }

    public function testUpdate() {
        $c = Company::findBy('name', 'Model Persister Company')->one();
        $c->name = 'Model Persister Company updated';
        $c->update();
        $c2 = Company::find($c->id);
        $this->assertEquals($c2->name, 'Model Persister Company updated');
    }

    public function testSave() {
        $c = new Company();
        $c->name = 'Model Persister Company 2';
        $c->save();
        $this->assertNotNull($c->id);
        $c->name = 'Model Persister Company 2 updated';
        $c->save();
        $c2 = Company::find($c->id);
        $this->assertEquals($c2->name, 'Model Persister Company 2 updated');
    }

    public function testDelete() {
        $c = Company::findBy('name', 'Model Persister Company 2 updated')->one();
        $c->delete();
        $cList = Company::findBy('name', 'Model Persister Company 2 updated');
        $this->assertEquals($cList->count(), 0);
    }

    public function testNoSave() {
        $p = new Project();
        $p->id = 'ABC';
        $p->name = 'abc';
        try {
            $p->save();
        } catch (EQMException $e) {
            $this->assertEquals($e->getMessage(), 'save() is not possible for entities without auto increment primary key');
        }
    }
}
