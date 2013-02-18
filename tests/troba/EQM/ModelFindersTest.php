<?php

namespace ModelTest;

use troba\Model\Finders;
use troba\EQM\EQM;

class Company
{
    use Finders;
}

class Project
{
    use Finders;
}

class ProjectActivity
{
    use Finders;
}

class ModelFindersTest extends \PHPUnit_Framework_TestCase
{
    public function testFind()
    {
        $cq = EQM::queryByPrimary(new Company(), 2);
        $cm = Company::find(2);
        $this->assertEquals($cq->id, $cm->id);
        $this->assertEquals($cq->name, $cm->name);

        $pq = EQM::queryByPrimary(new Project(), '2_2_PROJECT');
        $pm = Project::find('2_2_PROJECT');
        $this->assertEquals($pq->id, $pm->id);
        $this->assertEquals($pq->name, $pm->name);

        $paq = EQM::queryByPrimary(new ProjectActivity(), ['id' => 100, 'projectId' => '2_2_PROJECT']);
        $pam = ProjectActivity::find(['id' => 100, 'projectId' => '2_2_PROJECT']);
        $this->assertEquals($paq->id, $pam->id);
        $this->assertEquals($paq->name, $pam->name);
    }

    public function testFindAll()
    {
        $cq = EQM::query(new Company())->result();
        $cm = Company::findAll();
        $this->assertEquals($cq->count(), $cm->count());
        $this->assertEquals($cq->one()->name, $cm->one()->name);
    }

    public function testFindBy()
    {
        $pq = EQM::query(new Project())->where('companyId = ?', 2)->result();
        $pm = Project::findBy('companyId', 2);
        $this->assertEquals($pq->count(), $pm->count());
        $this->assertEquals($pq->one()->name, $pm->one()->name);
    }

    public function testOtherFind()
    {
        $pq = EQM::query(new Project())->where('companyId = ?', 2)->result();
        $pm = Project::findByCompanyId(2);
        $this->assertEquals($pq->count(), $pm->count());
        $this->assertEquals($pq->one()->name, $pm->one()->name);
    }

    public function testQuery()
    {
        $result = Company::query()->result();
        $this->assertInstanceOf('troba\\EQM\\ResultSetInterface', $result);

        $result = Company::query()
            ->innerJoin(new Project(), 'Company.id = Project.companyId')
            ->innerJoin(new ProjectActivity(), 'Project.id = ProjectActivity.projectId')
            ->where('ProjectActivity.id = :id', ['id' => 100])
            ->groupBy('Company.id')
            ->result();
        $this->assertEquals(27, $result->count());

    }
}
