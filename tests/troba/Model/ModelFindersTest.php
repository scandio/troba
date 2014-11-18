<?php

namespace ModelFindersTest;

use troba\Model\Finders;
use troba\EQM\EQM;

class Company {

    use Finders;
}

class Project {

    use Finders;
}

class ProjectActivity {

    use Finders;
}

class ModelFindersTest extends \PHPUnit_Framework_TestCase {

    public function testFind() {
        $cq = EQM::queryByPrimary(Company::class, 2);
        $cm = Company::find(2);
        $this->assertEquals($cq->id, $cm->id);
        $this->assertEquals($cq->name, $cm->name);

        $pq = EQM::queryByPrimary(Project::class, '2_2_PROJECT');
        $pm = Project::find('2_2_PROJECT');
        $this->assertEquals($pq->id, $pm->id);
        $this->assertEquals($pq->name, $pm->name);

        $paq = EQM::queryByPrimary(ProjectActivity::class, ['id' => 100, 'projectId' => '2_2_PROJECT']);
        $pam = ProjectActivity::find(['id' => 100, 'projectId' => '2_2_PROJECT']);
        $this->assertEquals($paq->id, $pam->id);
        $this->assertEquals($paq->name, $pam->name);
    }

    public function testFindAll() {
        $cq = EQM::query(Company::class)->result();
        $cm = Company::findAll();
        $this->assertEquals($cq->count(), $cm->count());
        $this->assertEquals($cq->one()->name, $cm->one()->name);
    }

    public function testFindBy() {
        $pq = EQM::query(Project::class)->where('companyId = ?', 2)->result();
        $pm = Project::findBy('companyId', 2);
        $this->assertEquals($pq->count(), $pm->count());
        $this->assertEquals($pq->one()->name, $pm->one()->name);
    }

    public function testOtherFind() {
        $pq = EQM::query(Project::class)->where('companyId = ?', 2)->result();
        $pm = Project::findByCompanyId(2);
        $this->assertEquals($pq->count(), $pm->count());
        $this->assertEquals($pq->one()->name, $pm->one()->name);
    }

    public function testQuery() {
        $result = Company::query()->result();
        $this->assertInstanceOf('troba\\EQM\\AbstractResultSet', $result);

        $result = Company::query()
            ->innerJoin(Project::class, 'Company.id = Project.companyId')
            ->innerJoin(ProjectActivity::class, 'Project.id = ProjectActivity.projectId')
            ->where('ProjectActivity.id = :id', ['id' => 100])
            ->groupBy('Company.id')
            ->result();
        $this->assertEquals(27, $result->count());

    }
}
