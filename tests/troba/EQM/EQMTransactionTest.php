<?php

class EQMTransactionTest extends PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $companyCount = \troba\EQM\EQM::query([
            'entity' => 'Company'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count();
        \troba\EQM\EQM::begin();
        $company = new Company();
        $company->name = 'Test for Transaction 1';
        $company->remark = 'Remark for Transaction 1';
        \troba\EQM\EQM::insert($company);
        \troba\EQM\EQM::insert($company);
        \troba\EQM\EQM::commit();
        $afterCompanyCount = \troba\EQM\EQM::query([
            'entity' => 'Company'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count();
        $this->assertEquals($companyCount + 2, $afterCompanyCount);

        \troba\EQM\EQM::begin();
        $company = new Company();
        $company->name = 'Test for Transaction 2';
        $company->remark = 'Remark for Transaction 2';
        \troba\EQM\EQM::insert($company);
        \troba\EQM\EQM::insert($company);
        \troba\EQM\EQM::rollBack();
        $companyCount = \troba\EQM\EQM::query([
            'entity' => 'Company'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count();
        $this->assertEquals($companyCount, $afterCompanyCount);
    }

    public function testComplex()
    {
        $companies = \troba\EQM\EQM::query([
            'entity' => 'Company'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $count = $companies->count();
        \troba\EQM\EQM::begin();
        foreach ($companies as $company) {
            $company->remark = 'Transaction remark';
            \troba\EQM\EQM::update($company);
            $projects = \troba\EQM\EQM::query([
                'entity' => new Project(),
                'query' => 'id = ?',
                'params' => $company->id
            ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
            foreach ($projects as $project) {
                $project->value = 999.99;
                \troba\EQM\EQM::update($project);
            }
        }
        \troba\EQM\EQM::rollBack();
        $companyCount = \troba\EQM\EQM::query([
            'entity' => 'Company'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY)->count();
        $this->assertEquals($count, $companyCount);
        $companies = \troba\EQM\EQM::query([
            'entity' => 'Company',
            'query' => 'remark = ?',
            'params' => 'Transaction remark'
        ], \troba\EQM\EQM::QUERY_TYPE_ARRAY);
        $this->assertEquals(0, $companies->count());
    }
}
