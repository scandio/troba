<?php
namespace Bootstrap;
# Load libs an register class loader

require_once '../lib/Scandio/Util/ClassLoader.php';
$loader = new \Scandio\Util\ClassLoader('Scandio', '../lib');
$loader->register();
# Connect to two data bases
# default
\Scandio\ORM\ORM::initialize([
    'dsn' => 'mysql:host=localhost;dbname=orm_test',
    'username' => 'root',
    'password' => 'root',
    \Scandio\ORM\ORM::RUN_MODE => \Scandio\ORM\ORM::DEV_MODE
]);
# second_db
\Scandio\ORM\ORM::initialize([
    'dsn' => 'mysql:host=localhost;dbname=orm_test2',
    'username' => 'root',
    'password' => 'root',
    \Scandio\ORM\ORM::CONVENTION_HANDLER => new \Scandio\ORM\ClassicConventionHandler(),
    \Scandio\ORM\ORM::RUN_MODE => \Scandio\ORM\ORM::DEV_MODE
], 'second_db');
# Delete all tables from 'default' connection
try {
    \Scandio\ORM\ORM::nativeExecute("DROP TABLE Company");
} catch (\Scandio\ORM\ORMException $e) {
    var_dump($e->getMessage());
}
try {
    \Scandio\ORM\ORM::nativeExecute("DROP TABLE Project");
} catch (\Scandio\ORM\ORMException $e) {
    var_dump($e->getMessage());
}
try {
    \Scandio\ORM\ORM::nativeExecute("DROP TABLE ProjectActivity");
} catch (\Scandio\ORM\ORMException $e) {
    var_dump($e->getMessage());
}
# Create all tables for 'default' connection
\Scandio\ORM\ORM::nativeExecute("
    CREATE TABLE Company (
        id int(11) unsigned NOT NULL AUTO_INCREMENT,
        name varchar(512) NOT NULL,
        remark varchar(512),
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
\Scandio\ORM\ORM::nativeExecute("
    CREATE TABLE Project (
        id varchar(255) NOT NULL,
        companyId int(11) unsigned NOT NULL,
        name varchar(255) NOT NULL,
        value decimal(10,2) DEFAULT 0,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");
\Scandio\ORM\ORM::nativeExecute("
    CREATE TABLE ProjectActivity (
        id int(11) unsigned NOT NULL,
        projectId varchar(255) NOT NULL,
        name varchar(255) NOT NULL,
        PRIMARY KEY (id, projectId)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");
# Switch to 'second_db' connection
\Scandio\ORM\ORM::activateConnection('second_db');
# Delete all tables from 'second_db_ connection
try {
    \Scandio\ORM\ORM::nativeExecute("DROP TABLE company");
} catch (\Scandio\ORM\ORMException $e) {
    var_dump($e->getMessage());
}
try {
    \Scandio\ORM\ORM::nativeExecute("DROP TABLE project");
} catch (\Scandio\ORM\ORMException $e) {
    var_dump($e->getMessage());
}
try {
    \Scandio\ORM\ORM::nativeExecute("DROP TABLE project_activity");
} catch (\Scandio\ORM\ORMException $e) {
    var_dump($e->getMessage());
}
# Create all tables for 'second_db' connection
\Scandio\ORM\ORM::nativeExecute("CREATE TABLE company (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
        name varchar(512) NOT NULL DEFAULT '',
        remark varchar(512),
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
\Scandio\ORM\ORM::nativeExecute("
    CREATE TABLE project (
        id varchar(255) NOT NULL,
        companyId int(11) unsigned NOT NULL,
        name varchar(255) NOT NULL,
        value decimal(10,2) DEFAULT 0,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");
\Scandio\ORM\ORM::nativeExecute("
    CREATE TABLE project_activity (
        id int(11) unsigned NOT NULL,
        projectId varchar(255) NOT NULL,
        name varchar(255) NOT NULL,
        PRIMARY KEY (id, projectId)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");
# Switch back to 'default' connection
\Scandio\ORM\ORM::activateConnection();
# Define classes
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

# Generate data
define('CNT_COMPANY', 27);
define('CNT_PROJECT', 9);
define('CNT_PROJECT_ACTIVITY', 7);
function generate($max_i, $max_j, $max_k)
{
    $c = new Company();
    for ($i = 0; $i < $max_i; $i++) {
        $c->name = ((isset($c->id)) ? $c->id + 1 : 1) . ' A Company Name';
        $c->remark = 'A remark for a company with the name ' . $c->name;
        \Scandio\ORM\ORM::insert($c);
        $p = new Project();
        for ($j = 0; $j < $max_j; $j++) {
            $p->id = $c->id . '_' . ($j + 1) . '_PROJECT';
            $p->companyId = $c->id;
            $p->name = 'A project with the id ' . $p->id;
            $p->value = ($j % 10) * 100 + ($c->id % ($j)) / $j;
            \Scandio\ORM\ORM::insert($p);
            $pa = new ProjectActivity();
            for ($k = 0; $k < $max_k; $k++) {
                $pa->id = 100 + $k;
                $pa->projectId = $p->id;
                $pa->name = 'Activity for ' . $p->id . ' with ' . $pa->id;
                \Scandio\ORM\ORM::insert($pa);
            }
        }
    }
}
generate(CNT_COMPANY, CNT_PROJECT, CNT_PROJECT_ACTIVITY);
# Generate data for 'second_db' connection
\Scandio\ORM\ORM::activateConnection('second_db');
generate(CNT_COMPANY, CNT_PROJECT, CNT_PROJECT_ACTIVITY);
# Switch back to 'default' connection
\Scandio\ORM\ORM::activateConnection();

