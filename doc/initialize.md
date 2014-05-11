# Initialize EQM

EQM is a static class but it is able to manage multiple connections.

## Basic connection

You can initialize troba if you call `EQM::initailze()` with a simple PDO object and an optional array as configuration
and an optional second parameter for a further connection name.

```php
EQM::initialize(new \PDO('mysql:host=localhost;dbname=orm_test', 'root', 'root'));
```

The configuration array has more parameters but there are a lot of defaults. The full array is this:

```php
$config = [
    EQM::CONVENTION_HANDLER => <object that implements ConventionHandlerInterface>
    EQM::SQL_BUILDER => <object that implements SqlBuilderInterface>
    EQM::LOGGER => <object of a Psr-3 compatible logger>
];

```
The defaults are

```php
$config = [
    EQM::CONVENTION_HANDLER => new troba\EQM\DefaultConventionHandler(),
    EQM::SQL_BUILDER => new troba\EQM\MySqlBuilder()
    EQM::LOGGER => new Psr\Log\NullLogger()
];
```

## Example full initialization

```php
<?php

require_once('vendor/autoload.php');

use troba\EQM\EQM;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

EQM::initialize(
    new \PDO(
        'mysql:host=localhost;dbname=orm_test2', 'root', 'root',
        [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"]
    ),
    [
        EQM::CONVENTION_HANDLER => new ClassicConventionHandler(),
        EQM::SQL_BUILDER => new MySqlBuilder(),
        EQM::LOGGER => new Logger('troba.test', new StreamHandler(__DIR__ . '/troba-test.log', Logger::ERROR))
    ],
    'my_connection'
);
```

## Multiple connections

The first call of `initialize()` sets the `'default'` connection. With `initialize($pdo, $config, $connectionName)`
you can add another connection with adifferent name. If you want to use another conntection than the default
connection you have to call `activateConnection($connectionName)`. The following example shows a table copy from
one database to another with a different convention handler that means another table naming.

```php
<?php

require_once('vendor/autoload.php');

class Company
{
}

use troba\EQM\EQM;

// init first connection
EQM::initialize(new \PDO('mysql:host=localhost;dbname=orm_test', 'root', 'root'));

// init second connection
EQM::initialize(new \PDO('mysql:host=localhost;dbname=orm_test2', 'root', 'root'), [], 'second_db');

// read all records from table Company in database orm_test
$companies = EQM::query(new Company())->result();

// switch to second connection
EQM::activateConnection('second');

// iterate over the result set and insert into
// the table company in the database orm_test2
foreach($companies as $company) {
    EQM::insert($company);
}

// reactivate default connection
EQM::activateConnection();
```
