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

class ORMQueryArrayTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleByString()
    {
        $this->assertEquals(CNT_COMPANY, count(\Scandio\ORM\ORM::query([
            'entity' => 'Company'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->all()));

        $this->assertEquals(CNT_COMPANY, count(\Scandio\ORM\ORM::query([
            'entity' => 'Company'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)));

        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => 'AnotherCompany'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT, \Scandio\ORM\ORM::query([
            'entity' => 'Project'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, \Scandio\ORM\ORM::query([
            'entity' => 'ProjectActivity'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());
    }

    public function testSimpleByStringAndNamespace()
    {
        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => 'Bootstrap\Company'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => 'Bootstrap\AnotherCompany'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT, \Scandio\ORM\ORM::query([
            'entity' => 'Bootstrap\Project'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, \Scandio\ORM\ORM::query([
            'entity' => 'Bootstrap\ProjectActivity'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());
    }

    public function testSimpleByObject()
    {
        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => new Company()
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => new AnotherCompany()
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT, \Scandio\ORM\ORM::query([
            'entity' => new Project()
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, \Scandio\ORM\ORM::query([
            'entity' => new ProjectActivity()
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());
    }

    public function testSimpleByObjectAndNamespace()
    {
        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Company()
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\AnotherCompany()
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\Project()
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\ProjectActivity()
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());
    }

    public function testSimpleWithClassicConvention()
    {
        \Scandio\ORM\ORM::activateConnection('second_db');
        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => 'Company'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => 'Bootstrap\Company'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, \Scandio\ORM\ORM::query([
            'entity' => new ProjectActivity()
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY * CNT_PROJECT * CNT_PROJECT_ACTIVITY, \Scandio\ORM\ORM::query([
            'entity' => new Bootstrap\ProjectActivity()
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());
        \Scandio\ORM\ORM::activateConnection();
    }

    public function testQueryWithSimpleParams()
    {
        $this->assertEquals(4, \Scandio\ORM\ORM::query([
            'entity' => 'Company',
            'query' => 'name = ?',
            'params' => '4 A Company Name'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->id);

        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => new Company(),
            'query' => 'Company.remark like ?',
            'params' => 'A remark for%'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => new AnotherCompany(),
            'query' => 'AnotherCompany.remark like ?',
            'params' => 'A remark for%'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());
    }

    public function testQueryWithNamedParams()
    {
        $this->assertEquals(4, \Scandio\ORM\ORM::query([
            'entity' => 'Company',
            'query' => 'name = :name',
            'params' => ['name' => '4 A Company Name']
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->id);

        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => new Company(),
            'query' => 'Company.remark like :remark',
            'params' => ['remark' => 'A remark for%']
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => new AnotherCompany(),
            'query' => 'AnotherCompany.remark like :remark',
            'params' => ['remark' => 'A remark for%']
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());
    }

    public function testQueryWithParamsClassic()
    {
        \Scandio\ORM\ORM::activateConnection('second_db');
        $this->assertEquals(4, \Scandio\ORM\ORM::query([
            'entity' => 'Company',
            'query' => 'name = :name',
            'params' => ['name' => '4 A Company Name']
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->id);

        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => new Company(),
            'query' => 'Company.remark like :remark',
            'params' => ['remark' => 'A remark for%']
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());

        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => new \Bootstrap\Company(),
            'query' => 'Company.remark like ?',
            'params' => 'A remark for%'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count());
        \Scandio\ORM\ORM::activateConnection();
    }

    public function testQueryWithOrder()
    {
        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => new \Bootstrap\Project(),
            'order' => 'companyId DESC'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->companyId);

        $this->assertEquals(CNT_COMPANY, \Scandio\ORM\ORM::query([
            'entity' => new AnotherCompany(),
            'order' => 'AnotherCompany.id DESC'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->id);

        $this->assertEquals(1, \Scandio\ORM\ORM::query([
            'entity' => new \Bootstrap\Project(),
            'order' => 'companyId'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->one()->companyId);
    }
}
