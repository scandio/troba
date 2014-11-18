<?php

use troba\EQM\EQM;
use troba\EQM\EQMException;

class EQMDeleteTest extends PHPUnit_Framework_TestCase {

    public function testDeleteDefault() {
        $companies = EQM::queryByArray([
            'entity' => Company::class,
            'order' => 'id'
        ]);
        $companiesCount = $companies->count();
        $company = $companies->one();

        $projects = EQM::queryByArray([
            'entity' => Project::class,
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ]);
        $projectsCount = $projects->count();

        $project = $projects->one();

        $projectActivites = EQM::queryByArray([
            'entity' => ProjectActivity::class,
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ]);
        $projectActivitesCount = $projectActivites->count();

        foreach ($projectActivites as $projectActivity) {
            EQM::delete($projectActivity);
        }

        $projectActivites = EQM::queryByArray([
            'entity' => ProjectActivity::class,
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ]);

        $this->assertEquals(0, $projectActivites->count());

        EQM::delete($project);
        $projects = EQM::queryByArray([
            'entity' => Project::class,
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ]);

        $this->assertEquals($projectsCount - 1, $projects->count());
    }


    public function testDeleteDefaultWithNamespace() {
        $companies = EQM::queryByArray([
            'entity' => Bootstrap\Company::class,
            'order' => 'id'
        ]);
        $companiesCount = $companies->count();
        $company = $companies->one();

        $projects = EQM::queryByArray([
            'entity' => Bootstrap\Project::class,
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ]);
        $projectsCount = $projects->count();

        $project = $projects->one();

        $projectActivites = EQM::queryByArray([
            'entity' => Bootstrap\ProjectActivity::class,
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ]);
        $projectActivitesCount = $projectActivites->count();

        foreach ($projectActivites as $projectActivity) {
            EQM::delete($projectActivity);
        }

        $projectActivites = EQM::queryByArray([
            'entity' => Bootstrap\ProjectActivity::class,
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ]);

        $this->assertEquals(0, $projectActivites->count());

        EQM::delete($project);
        $projects = EQM::queryByArray([
            'entity' => Bootstrap\Project::class,
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ]);

        $this->assertEquals($projectsCount - 1, $projects->count());
    }

    public function testDeleteClassic() {
        EQM::activateConnection('second_db');
        $companies = EQM::queryByArray([
            'entity' => Company::class,
            'order' => 'id'
        ]);
        $companiesCount = $companies->count();
        $company = $companies->one();

        $projects = EQM::queryByArray([
            'entity' => Project::class,
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ]);
        $projectsCount = $projects->count();

        $project = $projects->one();

        $projectActivites = EQM::queryByArray([
            'entity' => ProjectActivity::class,
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ]);
        $projectActivitesCount = $projectActivites->count();

        foreach ($projectActivites as $projectActivity) {
            EQM::delete($projectActivity);
        }

        $projectActivites = EQM::queryByArray([
            'entity' => ProjectActivity::class,
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ]);

        $this->assertEquals(0, $projectActivites->count());

        EQM::delete($project);
        $projects = EQM::queryByArray([
            'entity' => Project::class,
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ]);

        $this->assertEquals($projectsCount - 1, $projects->count());
        EQM::activateConnection();
    }

    public function testDeleteClassicWithNamespace() {
        EQM::activateConnection('second_db');
        $companies = EQM::queryByArray([
            'entity' => Bootstrap\Company::class,
            'order' => 'id'
        ]);
        $companiesCount = $companies->count();
        $company = $companies->one();

        $projects = EQM::queryByArray([
            'entity' => Bootstrap\Project::class,
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ]);
        $projectsCount = $projects->count();

        $project = $projects->one();

        $projectActivites = EQM::queryByArray([
            'entity' => Bootstrap\ProjectActivity::class,
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ]);
        $projectActivitesCount = $projectActivites->count();

        foreach ($projectActivites as $projectActivity) {
            EQM::delete($projectActivity);
        }

        $projectActivites = EQM::queryByArray([
            'entity' => Bootstrap\ProjectActivity::class,
            'query' => 'ProjectActivity.projectId = ?',
            'params' => $project->id
        ]);

        $this->assertEquals(0, $projectActivites->count());

        EQM::delete($project);
        $projects = EQM::queryByArray([
            'entity' => Bootstrap\Project::class,
            'query' => 'Project.companyId = ?',
            'params' => $company->id
        ]);

        $this->assertEquals($projectsCount - 1, $projects->count());
        EQM::activateConnection();
    }

    public function testDeleteError() {
        $company = new Company();
        try {
            EQM::delete($company);
            $r = true;
        } catch (EQMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $projectActivity = new ProjectActivity();
        try {
            EQM::delete($projectActivity);
            $r = true;
        } catch (EQMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $projectActivity->id = 999;
        try {
            EQM::delete($projectActivity);
            $r = true;
        } catch (EQMException $e) {
            $r = false;
        }
        $this->assertFalse($r);

        $projectActivity->id = 999;
        $projectActivity->projectId = 'WHAT_EVER';
        try {
            EQM::delete($projectActivity);
            # TODO zero result nothing has been deleted
            $r = true;
        } catch (EQMException $e) {
            $r = false;
        }
        $this->assertTrue($r);

    }
}