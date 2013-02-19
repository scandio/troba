<?php

use troba\EQM\EQM;

class Company
{
}

class AnotherCompany
{
    private $__table = 'Company';
}

class Project
{
}

class ProjectActivity
{
}

class EQMQueryArrayTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleByString()
    {
        $this->assertEquals(CNT_COMPANY, count(EQM::queryByArray([
            'entity' => 'Company'
        ])->all()));

        $this->assertEquals(CNT_COMPANY, count(EQM::queryByArray([
            'entity' => 'Company'
        ])));

        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => 'AnotherCompany'
        ])->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT, EQM::queryByArray([
            'entity' => 'Project'
        ])->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, EQM::queryByArray([
            'entity' => 'ProjectActivity'
        ])->count());
    }

    public function testSimpleByStringAndNamespace()
    {
        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => 'Bootstrap\Company'
        ])->count());

        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => 'Bootstrap\AnotherCompany'
        ])->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT, EQM::queryByArray([
            'entity' => 'Bootstrap\Project'
        ])->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, EQM::queryByArray([
            'entity' => 'Bootstrap\ProjectActivity'
        ])->count());
    }

    public function testSimpleByObject()
    {
        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => new Company()
        ])->count());

        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => new AnotherCompany()
        ])->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT, EQM::queryByArray([
            'entity' => new Project()
        ])->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, EQM::queryByArray([
            'entity' => new ProjectActivity()
        ])->count());
    }

    public function testSimpleByObjectAndNamespace()
    {
        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => new Bootstrap\Company()
        ])->count());

        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => new Bootstrap\AnotherCompany()
        ])->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT, EQM::queryByArray([
            'entity' => new Bootstrap\Project()
        ])->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, EQM::queryByArray([
            'entity' => new Bootstrap\ProjectActivity()
        ])->count());
    }

    public function testSimpleWithClassicConvention()
    {
        EQM::activateConnection('second_db');
        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => 'Company'
        ])->count());

        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => 'Bootstrap\Company'
        ])->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, EQM::queryByArray([
            'entity' => new ProjectActivity()
        ])->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, EQM::queryByArray([
            'entity' => new Bootstrap\ProjectActivity()
        ])->count());
        EQM::activateConnection();
    }

    public function testQueryWithSimpleParams()
    {
        $this->assertEquals(4, EQM::queryByArray([
            'entity' => 'Company',
            'query' => 'name = ?',
            'params' => '4 A Company Name'
        ])->one()->id);

        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => new Company(),
            'query' => 'Company.remark like ?',
            'params' => 'A remark for%'
        ])->count());

        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => new AnotherCompany(),
            'query' => 'AnotherCompany.remark like ?',
            'params' => 'A remark for%'
        ])->count());
    }

    public function testQueryWithNamedParams()
    {
        $this->assertEquals(4, EQM::queryByArray([
            'entity' => 'Company',
            'query' => 'name = :name',
            'params' => ['name' => '4 A Company Name']
        ])->one()->id);

        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => new Company(),
            'query' => 'Company.remark like :remark',
            'params' => ['remark' => 'A remark for%']
        ])->count());

        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => new AnotherCompany(),
            'query' => 'AnotherCompany.remark like :remark',
            'params' => ['remark' => 'A remark for%']
        ])->count());
    }

    public function testQueryWithParamsClassic()
    {
        EQM::activateConnection('second_db');
        $this->assertEquals(4, EQM::queryByArray([
            'entity' => 'Company',
            'query' => 'name = :name',
            'params' => ['name' => '4 A Company Name']
        ])->one()->id);

        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => new Company(),
            'query' => 'Company.remark like :remark',
            'params' => ['remark' => 'A remark for%']
        ])->count());

        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => new \Bootstrap\Company(),
            'query' => 'Company.remark like ?',
            'params' => 'A remark for%'
        ])->count());
        EQM::activateConnection();
    }

    public function testQueryWithOrder()
    {
        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => new \Bootstrap\Project(),
            'order' => 'companyId DESC'
        ])->one()->companyId);

        $this->assertEquals(CNT_COMPANY, EQM::queryByArray([
            'entity' => new AnotherCompany(),
            'order' => 'AnotherCompany.id DESC'
        ])->one()->id);

        $this->assertEquals(1, EQM::queryByArray([
            'entity' => new \Bootstrap\Project(),
            'order' => 'companyId'
        ])->one()->companyId);
    }
}
