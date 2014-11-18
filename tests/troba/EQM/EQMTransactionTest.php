<?php

use troba\EQM\EQM;

class EQMTransactionTest extends PHPUnit_Framework_TestCase {

    public function testDefault() {
        $companyCount = EQM::queryByArray([
            'entity' => 'Company'
        ])->count();
        EQM::begin();
        $company = new Company();
        $company->name = 'Test for Transaction 1';
        $company->remark = 'Remark for Transaction 1';
        EQM::insert($company);
        EQM::insert($company);
        EQM::commit();
        $afterCompanyCount = EQM::queryByArray([
            'entity' => 'Company'
        ])->count();
        $this->assertEquals($companyCount + 2, $afterCompanyCount);

        EQM::begin();
        $company = new Company();
        $company->name = 'Test for Transaction 2';
        $company->remark = 'Remark for Transaction 2';
        EQM::insert($company);
        EQM::insert($company);
        EQM::rollBack();
        $companyCount = EQM::queryByArray([
            'entity' => 'Company'
        ])->count();
        $this->assertEquals($companyCount, $afterCompanyCount);
    }

    public function testComplex() {
        $companies = EQM::queryByArray([
            'entity' => 'Company'
        ]);
        $count = $companies->count();
        EQM::begin();
        foreach ($companies as $company) {
            $company->remark = 'Transaction remark';
            EQM::update($company);
            $projects = EQM::queryByArray([
                'entity' => Project::class,
                'query' => 'id = ?',
                'params' => $company->id
            ]);
            foreach ($projects as $project) {
                $project->value = 999.99;
                EQM::update($project);
            }
        }
        EQM::rollBack();
        $companyCount = EQM::queryByArray([
            'entity' => 'Company'
        ])->count();
        $this->assertEquals($count, $companyCount);
        $companies = EQM::queryByArray([
            'entity' => 'Company',
            'query' => 'remark = ?',
            'params' => 'Transaction remark'
        ]);
        $this->assertEquals(0, $companies->count());
    }
}