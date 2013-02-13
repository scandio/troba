<?php

class EQMDeleteTest extends PHPUnit_Framework_TestCase
{
    public function testDeleteDefault()
    {
        $companies = \troba\EQM\EQM::query([
            'entity' => new Company(),
            'order' => 'id'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $companiesCount = $companies->count();
        $company = $companies->one();

        $projects = \troba\EQM\EQM::query([
            'entity' => new Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $projectsCount = $projects->count();

        $project = $projects->one();

        $projectActivites = \troba\EQM\EQM::query([
            'entity' => new ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $projectActivitesCount = $projectActivites->count();

        foreach ($projectActivites as $projectActivity) {
            \troba\EQM\EQM::delete($projectActivity);
        }

        $projectActivites = \troba\EQM\EQM::query([
            'entity' => new ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);

        $this->assertEquals(0, $projectActivites->count());

        \troba\EQM\EQM::delete($project);
        $projects = \troba\EQM\EQM::query([
            'entity' => new Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);

        $this->assertEquals($projectsCount - 1, $projects->count());
    }


    public function testDeleteDefaultWithNamespace()
    {
        $companies = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Company(),
            'order' => 'id'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $companiesCount = $companies->count();
        $company = $companies->one();

        $projects = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $projectsCount = $projects->count();

        $project = $projects->one();

        $projectActivites = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $projectActivitesCount = $projectActivites->count();

        foreach ($projectActivites as $projectActivity) {
            \troba\EQM\EQM::delete($projectActivity);
        }

        $projectActivites = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);

        $this->assertEquals(0, $projectActivites->count());

        \troba\EQM\EQM::delete($project);
        $projects = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);

        $this->assertEquals($projectsCount - 1, $projects->count());
    }

    public function testDeleteClassic()
    {
        \troba\EQM\EQM::activateConnection('second_db');
        $companies = \troba\EQM\EQM::query([
            'entity' => new Company(),
            'order' => 'id'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $companiesCount = $companies->count();
        $company = $companies->one();

        $projects = \troba\EQM\EQM::query([
            'entity' => new Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $projectsCount = $projects->count();

        $project = $projects->one();

        $projectActivites = \troba\EQM\EQM::query([
            'entity' => new ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $projectActivitesCount = $projectActivites->count();

        foreach ($projectActivites as $projectActivity) {
            \troba\EQM\EQM::delete($projectActivity);
        }

        $projectActivites = \troba\EQM\EQM::query([
            'entity' => new ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);

        $this->assertEquals(0, $projectActivites->count());

        \troba\EQM\EQM::delete($project);
        $projects = \troba\EQM\EQM::query([
            'entity' => new Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);

        $this->assertEquals($projectsCount - 1, $projects->count());
        \troba\EQM\EQM::activateConnection();
    }

    public function testDeleteClassicWithNamespace()
    {
        \troba\EQM\EQM::activateConnection('second_db');
        $companies = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Company(),
            'order' => 'id'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $companiesCount = $companies->count();
        $company = $companies->one();

        $projects = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $projectsCount = $projects->count();

        $project = $projects->one();

        $projectActivites = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $projectActivitesCount = $projectActivites->count();

        foreach ($projectActivites as $projectActivity) {
            \troba\EQM\EQM::delete($projectActivity);
        }

        $projectActivites = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);

        $this->assertEquals(0, $projectActivites->count());

        \troba\EQM\EQM::delete($project);
        $projects = \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);

        $this->assertEquals($projectsCount - 1, $projects->count());
        \troba\EQM\EQM::activateConnection();
    }

    public function testDeleteError()
    {
        $company = new Company();
        try {
            \troba\EQM\EQM::delete($company);
            $r = true;
        } catch (\troba\EQM\EQMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $projectActivity = new ProjectActivity();
        try {
            \troba\EQM\EQM::delete($projectActivity);
            $r = true;
        } catch (\troba\EQM\EQMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $projectActivity->id = 999;
        try {
            \troba\EQM\EQM::delete($projectActivity);
            $r = true;
        } catch (\troba\EQM\EQMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $projectActivity->id = 999;
        $projectActivity->projectId = 'WHAT_EVER';
        try {
            \troba\EQM\EQM::delete($projectActivity);
            # TODO zero result nothing has been deleted
            $r = true;
        } catch (\troba\EQM\EQMException $e) {
            $r = false;
        }
        $this->assertTrue($r);

    }
}
