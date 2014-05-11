<?php
namespace Bootstrap;

require_once '../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use \troba\EQM\EQM;
use troba\EQM\ClassicConventionHandler;
use troba\EQM\EQMException;

// create logger object
$logger = new Logger('troba-test', [
        new StreamHandler(__DIR__ . '/troba-tests.log',
            Logger::ERROR
        )
    ]
);

// Connect to two databases
// default
EQM::initialize(
    new \PDO(
        'mysql:host=localhost;dbname=orm_test', 'root', 'root',
        [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"]
    ),
    [
        EQM::LOGGER => $logger
    ]
);
// second_db
EQM::initialize(
    new \PDO(
        'mysql:host=localhost;dbname=orm_test2', 'root', 'root',
        [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"]
    ),
    [
        EQM::CONVENTION_HANDLER => new ClassicConventionHandler(),
        EQM::LOGGER => $logger
    ],
    'second_db'
);

// Delete all tables from 'default' connection
try {
    EQM::nativeExecute("DROP TABLE Company");
} catch (EQMException $e) {
    $logger->error($e->getMessage());
}
try {
    EQM::nativeExecute("DROP TABLE Project");
} catch (EQMException $e) {
    $logger->error($e->getMessage());
}
try {
    EQM::nativeExecute("DROP TABLE ProjectActivity");
} catch (EQMException $e) {
    $logger->error($e->getMessage());
}

// Create all tables for 'default' connection
EQM::nativeExecute("
    CREATE TABLE Company (
        id int(11) unsigned NOT NULL AUTO_INCREMENT,
        name varchar(512) NOT NULL,
        remark varchar(512),
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

EQM::nativeExecute("
    CREATE TABLE Project (
        id varchar(255) NOT NULL,
        companyId int(11) unsigned NOT NULL,
        name varchar(255) NOT NULL,
        value decimal(10,2) DEFAULT 0,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

EQM::nativeExecute("
    CREATE TABLE ProjectActivity (
        id int(11) unsigned NOT NULL,
        projectId varchar(255) NOT NULL,
        name varchar(255) NOT NULL,
        PRIMARY KEY (id, projectId)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

// Switch to 'second_db' connection
EQM::activateConnection('second_db');
// Delete all tables from 'second_db_ connection
try {
    EQM::nativeExecute("DROP TABLE company");
} catch (EQMException $e) {
    $logger->error($e->getMessage());
}
try {
    EQM::nativeExecute("DROP TABLE project");
} catch (EQMException $e) {
    $logger->error($e->getMessage());
}
try {
    EQM::nativeExecute("DROP TABLE project_activity");
} catch (EQMException $e) {
    $logger->error($e->getMessage());
}
// Create all tables for 'second_db' connection
EQM::nativeExecute("CREATE TABLE company (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
        name varchar(512) NOT NULL DEFAULT '',
        remark varchar(512),
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

EQM::nativeExecute("
    CREATE TABLE project (
        id varchar(255) NOT NULL,
        companyId int(11) unsigned NOT NULL,
        name varchar(255) NOT NULL,
        value decimal(10,2) DEFAULT 0,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

EQM::nativeExecute("
    CREATE TABLE project_activity (
        id int(11) unsigned NOT NULL,
        projectId varchar(255) NOT NULL,
        name varchar(255) NOT NULL,
        PRIMARY KEY (id, projectId)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

// Switch back to 'default' connection
EQM::activateConnection();

// Define classes
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

// Generate test data
define('CNT_COMPANY', 27);
define('CNT_PROJECT', 9);
define('CNT_PROJECT_ACTIVITY', 7);

function generate($max_i, $max_j, $max_k)
{
    $c = new Company();
    for ($i = 0; $i < $max_i; $i++) {
        $c->name = ((isset($c->id)) ? $c->id + 1 : 1) . ' A Company Name';
        $c->remark = 'A remark for a company with the name ' . $c->name;
        EQM::insert($c);
        $p = new Project();
        for ($j = 0; $j < $max_j; $j++) {
            $p->id = $c->id . '_' . ($j + 1) . '_PROJECT';
            $p->companyId = $c->id;
            $p->name = 'A project with the id ' . $p->id;
            $p->value = ($j % 10) * 100 + ($c->id % ($j)) / $j;
            EQM::insert($p);
            $pa = new ProjectActivity();
            for ($k = 0; $k < $max_k; $k++) {
                $pa->id = 100 + $k;
                $pa->projectId = $p->id;
                $pa->name = 'Activity for ' . $p->id . ' with ' . $pa->id;
                EQM::insert($pa);
            }
        }
    }
}

generate(CNT_COMPANY, CNT_PROJECT, CNT_PROJECT_ACTIVITY);

// Generate data for 'second_db' connection
EQM::activateConnection('second_db');
generate(CNT_COMPANY, CNT_PROJECT, CNT_PROJECT_ACTIVITY);

// Switch back to 'default' connection
EQM::activateConnection();

