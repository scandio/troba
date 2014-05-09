<?php

namespace ModelPersistersTest;

use troba\Model\Finders;
use troba\EQM\EQM;
use troba\Model\Persisters;

class Company
{
    use Finders;
    use Persisters;
}

class Project
{
    use Finders;
    use Persisters;
}

class ProjectActivity
{
    use Finders;
    use Persisters;
}

class ModelPersistersTest extends \PHPUnit_Framework_TestCase
{
    public function testInsert()
    {
        $c = new Company();
        $c->name = 'Model Persister Company';
        $c->insert();
        $this->assertNotNull($c->id);
    }
}
