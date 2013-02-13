<?php

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
        $this->assertEquals(CNT_COMPANY, count(\troba\EQM\EQM::query([
            'entity' => 'Company'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->all()));

        $this->assertEquals(CNT_COMPANY, count(\troba\EQM\EQM::query([
            'entity' => 'Company'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)));

        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => 'AnotherCompany'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT, \troba\EQM\EQM::query([
            'entity' => 'Project'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, \troba\EQM\EQM::query([
            'entity' => 'ProjectActivity'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());
    }

    public function testSimpleByStringAndNamespace()
    {
        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => 'Bootstrap\Company'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => 'Bootstrap\AnotherCompany'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT, \troba\EQM\EQM::query([
            'entity' => 'Bootstrap\Project'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, \troba\EQM\EQM::query([
            'entity' => 'Bootstrap\ProjectActivity'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());
    }

    public function testSimpleByObject()
    {
        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => new Company()
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => new AnotherCompany()
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT, \troba\EQM\EQM::query([
            'entity' => new Project()
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, \troba\EQM\EQM::query([
            'entity' => new ProjectActivity()
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());
    }

    public function testSimpleByObjectAndNamespace()
    {
        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Company()
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => new Bootstrap\AnotherCompany()
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT, \troba\EQM\EQM::query([
            'entity' => new Bootstrap\Project()
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, \troba\EQM\EQM::query([
            'entity' => new Bootstrap\ProjectActivity()
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());
    }

    public function testSimpleWithClassicConvention()
    {
        \troba\EQM\EQM::activateConnection('second_db');
        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => 'Company'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => 'Bootstrap\Company'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, \troba\EQM\EQM::query([
            'entity' => new ProjectActivity()
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, \troba\EQM\EQM::query([
            'entity' => new Bootstrap\ProjectActivity()
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());
        \troba\EQM\EQM::activateConnection();
    }

    public function testQueryWithSimpleParams()
    {
        $this->assertEquals(4, \troba\EQM\EQM::query([
            'entity' => 'Company',
            'query' => 'name = ?',
            'params' => '4 A Company Name'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->id);

        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => new Company(),
            'query' => 'Company.remark like ?',
            'params' => 'A remark for%'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => new AnotherCompany(),
            'query' => 'AnotherCompany.remark like ?',
            'params' => 'A remark for%'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());
    }

    public function testQueryWithNamedParams()
    {
        $this->assertEquals(4, \troba\EQM\EQM::query([
            'entity' => 'Company',
            'query' => 'name = :name',
            'params' => ['name' => '4 A Company Name']
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->id);

        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => new Company(),
            'query' => 'Company.remark like :remark',
            'params' => ['remark' => 'A remark for%']
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => new AnotherCompany(),
            'query' => 'AnotherCompany.remark like :remark',
            'params' => ['remark' => 'A remark for%']
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());
    }

    public function testQueryWithParamsClassic()
    {
        \troba\EQM\EQM::activateConnection('second_db');
        $this->assertEquals(4, \troba\EQM\EQM::query([
            'entity' => 'Company',
            'query' => 'name = :name',
            'params' => ['name' => '4 A Company Name']
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->id);

        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => new Company(),
            'query' => 'Company.remark like :remark',
            'params' => ['remark' => 'A remark for%']
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => new \Bootstrap\Company(),
            'query' => 'Company.remark like ?',
            'params' => 'A remark for%'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count());
        \troba\EQM\EQM::activateConnection();
    }

    public function testQueryWithOrder()
    {
        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => new \Bootstrap\Project(),
            'order' => 'companyId DESC'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->companyId);

        $this->assertEquals(CNT_COMPANY, \troba\EQM\EQM::query([
            'entity' => new AnotherCompany(),
            'order' => 'AnotherCompany.id DESC'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->id);

        $this->assertEquals(1, \troba\EQM\EQM::query([
            'entity' => new \Bootstrap\Project(),
            'order' => 'companyId'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->one()->companyId);
    }
}
