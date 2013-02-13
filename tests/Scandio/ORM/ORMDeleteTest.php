<?php

class ORMDeleteTest extends PHPUnit_Framework_TestCase
{
    public function testDeleteDefault()
    {
        $companies = \Scandio\ORM\ORM::query([
            'entity' => new Company(),
            'order' => 'id'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $companiesCount = $companies->count();
        $company = $companies->one();

        $projects = \Scandio\ORM\ORM::query([
            'entity' => new Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $projectsCount = $projects->count();

        $project = $projects->one();

        $projectActivites = \Scandio\ORM\ORM::query([
            'entity' => new ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $projectActivitesCount = $projectActivites->count();

        foreach ($projectActivites as $projectActivity) {
            \Scandio\ORM\ORM::delete($projectActivity);
        }

        $projectActivites = \Scandio\ORM\ORM::query([
            'entity' => new ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);

        $this->assertEquals(0, $projectActivites->count());

        \Scandio\ORM\ORM::delete($project);
        $projects = \Scandio\ORM\ORM::query([
            'entity' => new Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);

        $this->assertEquals($projectsCount - 1, $projects->count());
    }


    public function testDeleteDefaultWithNamespace()
    {
        $companies = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Company(),
            'order' => 'id'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $companiesCount = $companies->count();
        $company = $companies->one();

        $projects = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $projectsCount = $projects->count();

        $project = $projects->one();

        $projectActivites = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $projectActivitesCount = $projectActivites->count();

        foreach ($projectActivites as $projectActivity) {
            \Scandio\ORM\ORM::delete($projectActivity);
        }

        $projectActivites = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);

        $this->assertEquals(0, $projectActivites->count());

        \Scandio\ORM\ORM::delete($project);
        $projects = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);

        $this->assertEquals($projectsCount - 1, $projects->count());
    }

    public function testDeleteClassic()
    {
        \Scandio\ORM\ORM::activateConnection('second_db');
        $companies = \Scandio\ORM\ORM::query([
            'entity' => new Company(),
            'order' => 'id'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $companiesCount = $companies->count();
        $company = $companies->one();

        $projects = \Scandio\ORM\ORM::query([
            'entity' => new Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $projectsCount = $projects->count();

        $project = $projects->one();

        $projectActivites = \Scandio\ORM\ORM::query([
            'entity' => new ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $projectActivitesCount = $projectActivites->count();

        foreach ($projectActivites as $projectActivity) {
            \Scandio\ORM\ORM::delete($projectActivity);
        }

        $projectActivites = \Scandio\ORM\ORM::query([
            'entity' => new ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);

        $this->assertEquals(0, $projectActivites->count());

        \Scandio\ORM\ORM::delete($project);
        $projects = \Scandio\ORM\ORM::query([
            'entity' => new Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);

        $this->assertEquals($projectsCount - 1, $projects->count());
        \Scandio\ORM\ORM::activateConnection();
    }

    public function testDeleteClassicWithNamespace()
    {
        \Scandio\ORM\ORM::activateConnection('second_db');
        $companies = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Company(),
            'order' => 'id'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $companiesCount = $companies->count();
        $company = $companies->one();

        $projects = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $projectsCount = $projects->count();

        $project = $projects->one();

        $projectActivites = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $projectActivitesCount = $projectActivites->count();

        foreach ($projectActivites as $projectActivity) {
            \Scandio\ORM\ORM::delete($projectActivity);
        }

        $projectActivites = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\ProjectActivity(),
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);

        $this->assertEquals(0, $projectActivites->count());

        \Scandio\ORM\ORM::delete($project);
        $projects = \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Project(),
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);

        $this->assertEquals($projectsCount - 1, $projects->count());
        \Scandio\ORM\ORM::activateConnection();
    }

    public function testDeleteError()
    {
        $company = new Company();
        try {
            \Scandio\ORM\ORM::delete($company);
            $r = true;
        } catch (\Scandio\ORM\ORMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $projectActivity = new ProjectActivity();
        try {
            \Scandio\ORM\ORM::delete($projectActivity);
            $r = true;
        } catch (\Scandio\ORM\ORMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $projectActivity->id = 999;
        try {
            \Scandio\ORM\ORM::delete($projectActivity);
            $r = true;
        } catch (\Scandio\ORM\ORMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $projectActivity->id = 999;
        $projectActivity->projectId = 'WHAT_EVER';
        try {
            \Scandio\ORM\ORM::delete($projectActivity);
            # TODO zero result nothing has been deleted
            $r = true;
        } catch (\Scandio\ORM\ORMException $e) {
            $r = false;
        }
        $this->assertTrue($r);

    }
}
