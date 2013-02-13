<?php

class ORMTransactionTest extends PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $companyCount = \Scandio\ORM\ORM::query([
            'entity' => 'Company'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count();
        \Scandio\ORM\ORM::begin();
        $company = new Company();
        $company->name = 'Test for Transaction 1';
        $company->remark = 'Remark for Transaction 1';
        \Scandio\ORM\ORM::insert($company);
        \Scandio\ORM\ORM::insert($company);
        \Scandio\ORM\ORM::commit();
        $afterCompanyCount = \Scandio\ORM\ORM::query([
            'entity' => 'Company'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count();
        $this->assertEquals($companyCount + 2, $afterCompanyCount);

        \Scandio\ORM\ORM::begin();
        $company = new Company();
        $company->name = 'Test for Transaction 2';
        $company->remark = 'Remark for Transaction 2';
        \Scandio\ORM\ORM::insert($company);
        \Scandio\ORM\ORM::insert($company);
        \Scandio\ORM\ORM::rollBack();
        $companyCount = \Scandio\ORM\ORM::query([
            'entity' => 'Company'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count();
        $this->assertEquals($companyCount, $afterCompanyCount);
    }

    public function testComplex()
    {
        $companies = \Scandio\ORM\ORM::query([
            'entity' => 'Company'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $count = $companies->count();
        \Scandio\ORM\ORM::begin();
        foreach ($companies as $company) {
            $company->remark = 'Transaction remark';
            \Scandio\ORM\ORM::update($company);
            $projects = \Scandio\ORM\ORM::query([
                'entity' => new Project(),
                'query' => 'id = ?',
                'params' => $company->id
            ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
            foreach ($projects as $project) {
                $project->value = 999.99;
                \Scandio\ORM\ORM::update($project);
            }
        }
        \Scandio\ORM\ORM::rollBack();
        $companyCount = \Scandio\ORM\ORM::query([
            'entity' => 'Company'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY)->count();
        $this->assertEquals($count, $companyCount);
        $companies = \Scandio\ORM\ORM::query([
            'entity' => 'Company',
            'query' => 'remark = ?',
            'params' => 'Transaction remark'
        ], \Scandio\ORM\ORM::QUERY_TYPE_ARRAY);
        $this->assertEquals(0, $companies->count());
    }
}
