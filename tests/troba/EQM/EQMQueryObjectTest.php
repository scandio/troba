<?php

use troba\EQM\EQM;

class EQMQueryObjectTest extends PHPUnit_Framework_TestCase {

    public function testDefaultSimpleQuery() {
        $count = EQM::queryByArray(['entity' => 'Company'])->count();
        $result = EQM::query('Company')->result();
        $this->assertEquals($count, $result->count());
        $resultArray = $result->all();
        $this->assertTrue($resultArray instanceof \ArrayAccess);
        $this->assertEquals($count, count($resultArray));
        $this->assertEquals('Company', get_class($resultArray[0]));
    }

    public function testDefaultJoinedQuery() {
        $result = EQM::query(Company::class)
            ->innerJoin(Project::class, 'Company.id = Project.companyId')
            ->where('Project.companyId = ?', 2)
            ->result();
        $this->assertEquals(CNT_PROJECT, $result->count());
        $this->assertEquals(2, $result->one()->id);
    }

    public function testDefaultLimitQuery() {
        $resultArrayAll = EQM::query('Bootstrap\\Project')->result()->all();
        $resultArrayLimited = EQM::query(Project::class)->limit(3)->result()->all();
        $this->assertEquals(3, count($resultArrayLimited));
        $this->assertEquals($resultArrayAll[1]->id, $resultArrayLimited[1]->id);
        $resultArrayLimited = EQM::query(\Bootstrap\Project::class)->result(3, 2)->all();
        $this->assertEquals(3, count($resultArrayLimited));
        $this->assertEquals($resultArrayAll[3]->id, $resultArrayLimited[1]->id);
    }

    public function testQueryWithoutEntity() {
        $result = EQM::query()->select('name, remark')->from('Company')->one();
        $this->assertArrayHasKey('name', get_object_vars($result));
        $this->assertArrayHasKey('remark', get_object_vars($result));
        $this->assertEquals(2, count(get_object_vars($result)));
    }

    public function testJoinedQueryWithoutEntity() {
        $result = EQM::query()
            ->select('Company.name company, Company.remark, Project.id project, ProjectActivity.id activity')
            ->from('Company')
            ->innerJoin('Project', 'Company.id = Project.companyId')
            ->innerJoin('ProjectActivity', 'Project.id = ProjectActivity.projectId')
            ->one();
        $this->assertArrayHasKey('company', get_object_vars($result));
        $this->assertArrayHasKey('project', get_object_vars($result));
        $this->assertArrayHasKey('remark', get_object_vars($result));
        $this->assertArrayHasKey('activity', get_object_vars($result));
        $this->assertEquals(4, count(get_object_vars($result)));
        $this->assertEquals(100, $result->activity);
    }

    public function testQueryMore() {
        $result = EQM::query()->select('Company.id id, Project.id projectId, ProjectActivity.id projectActivityId')
            ->from(Company::class)
            ->leftJoin(Project::class, 'Company.id = Project.companyId')
            ->leftJoin(ProjectActivity::class, 'Project.id = ProjectActivity.projectId')
            ->where('Company.id = ?', [28])
            ->result();
        $this->assertNull($result->one()->projectId);
        $result = EQM::query(Company::class)
            ->where('Company.id < ?', [100])
            ->andWhere('Company.name LIKE ?', ['A%'])
            ->orWhere('Company.remark LIKE ?', ['A%'])
            ->result();
        $this->assertEquals($result->count(), 31);
    }

    public function testSortedQuery() {
        $result = EQM::query('Company')->orderBy('id DESC')->one();
        $this->assertEquals(CNT_COMPANY + 6, $result->id);
        $result->name = 'ZZZ' . $result->name;
        EQM::update($result);
        $result = EQM::query('Company')->orderBy('name DESC')->one();
        $this->assertEquals('ZZZ', substr($result->name, 0, 3));
    }

    public function testGroupedQuery() {
        $result = EQM::query('Company')
            ->innerJoin('Project', 'Company.id = Project.companyId')
            ->innerJoin('ProjectActivity', 'Project.id = ProjectActivity.projectId')
            ->where('ProjectActivity.id = :id', ['id' => 100])
            ->groupBy('Company.id')
            ->result();
        $this->assertEquals(27, $result->count());
    }

    public function testAliasing() {
        $result = EQM::query()->select('*')->from('Company Firma')->where('Firma.id = ?', 2)->result()->one();
        $this->assertEquals(2, $result->id);

        $result = EQM::query('Company Firma')
            ->innerJoin('Project Projekt', 'Firma.id = Projekt.companyId')
            ->innerJoin('ProjectActivity Aktivitaet', 'Projekt.id = Aktivitaet.projectId')
            ->where('Aktivitaet.id = :id', ['id' => 100])
            ->groupBy('Firma.id')
            ->result();
        $this->assertEquals(27, $result->count());
    }
}