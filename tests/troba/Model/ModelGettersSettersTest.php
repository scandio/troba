<?php

namespace ModelGettersSettersTest;

use troba\EQM\EQMException;
use troba\Model\Finders;
use troba\Model\Getters;
use troba\Model\Persisters;
use troba\Model\Setters;

class Company {

    use Getters;
    use Setters;
    use Finders;
    use Persisters;

    public $id;
    public $name;
    public $remark;
}

class Project {

    use Getters;
    use Setters;
    use Finders;
    use Persisters;

    protected $id;
    protected $companyId;
    protected $name;
    protected $value;

    public function getId() {
        return $this->id;
    }

    public function getCompanyId() {
        return $this->companyId;
    }

    public function getName() {
        return $this->name;
    }

    public function getValue() {
        return $this->value;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setValue($value) {
        $this->value = $value;
    }
}

class ProjectActivity {

    use Getters;
    use Setters;
    use Finders;
    use Persisters;

    public $id;
    public $projectId;
    public $name;

    public function getProject() {
        return Project::find($this->projectId);
    }

    public function getSenseless($args = []) {
        return 'senseless_' . $args[0];
    }
}

class ACompany {

    use Setters;

    private $__table = "Company";
}

class ModelGettersSettersTest extends \PHPUnit_Framework_TestCase {

    public function testDefault() {
        $c = new Company();
        $c->name = 'Model Getter Setter Company';
        $c->save();
        $this->assertNotNull($c->id);
        $this->assertEquals($c->name, 'Model Getter Setter Company');
    }

    public function testProtected() {
        $c = Company::findByName('Model Getter Setter Company')->one();
        $p = new Project();
        $p->id = 'PROJECT_' . $c->id;
        $p->companyId = $c->id;
        $p->name = 'Model Getter Setter Project';
        $p->insert();
        $this->assertNotNull($p->id);
        $p2 = Project::find($p->id);
        $this->assertEquals($p->id, $p2->id);
        $this->assertEquals($p->name, $p2->name);
    }

    public function testAnyRelation() {
        $p = Project::findByName('Model Getter Setter Project')->one();
        $pa = new ProjectActivity();
        $pa->id = 100;
        $pa->projectId = $p->id;
        $pa->name = 'Model Getter Setter Project Activity';
        $pa->insert();
        $this->assertNotNull($pa->id);
        $this->assertEquals($pa->project->id, $p->id);
        $this->assertEquals($pa->project()->id, $p->id);
        $this->assertEquals($pa->getSenseless(['value']), 'senseless_value');
        $this->assertEquals($pa->senseless('value'), 'senseless_value');
    }

    public function testNonExisting() {
        try {
            $p = new Project();
            $p->nonExisting();
        } catch (EQMException $e) {
            $this->assertEquals($e->getMessage(), 'Method or property does not exists');
        }
    }

    public function testPrivateException() {
        $c = new ACompany();
        try {
            $c->name = 'abc';
        } catch (EQMException $e) {
            $this->assertEquals($e->getMessage(), 'private or protected properties are not accessible');
        }
    }
}
