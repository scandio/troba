# Initialize EQM / DDL

EQM is a static class but it is able to manage multiple connections.

## Basic connection

You can initialize troba if you call `EQM::initailze()` with an array as configuration
and an optional second parameter for a second connection name.

```php
EQM::initialize([
    'dsn' => '<pdo dsn>',
    'username' => '<username>',
    'password' => '<password>'
]);
```

The configuration array has more parameters but there are a lot of defaults. The full array is this:

```php
$config = [
    'dsn' => '<the pdo dsn>',
    'username' => '<the username if necessary>'
    'password' => 'the password if necessary>',
    EQM::RUN_MODE => [EQM::DEV_MODE | EQM::PROD_MODE]
    EQM::CONVENTION_HANDLER => <object that implements ConventionHandlerInterface>
    EQM::SQL_BUILDER => <object that implements SqlBuilderInterface>
];

```
The defaults are
```php
$config = [
    'dsn' => '<the pdo dsn>',
    'username' => '<the username if necessary>'
    'password' => 'the password if necessary>',
    EQM::RUN_MODE => EQM::PROD_MODE,
    EQM::CONVENTION_HANDLER => new troba\EQM\DefaultConventionHandler(),
    EQM::SQL_BUILDER => new troba\EQM\MySqlBuilder()
];

```

## Multiple connections

The first call of `initialize()` sets the `'default'` connection. With `initialize($config, $connectionName)`
you can add another connection with adifferent name. If you want to use another conntection than the default
connection you have to call `activateConnection($connectionName)`. The following example shows a table copy from
one database to another with a different convention handler that means another table naming.

```php
require_once '../troba/lib/troba/Util/ClassLoader.php';
$loader = new \troba\Util\ClassLoader('troba', '../troba/lib');
$loader->register();

require_once '../vendor/autoload.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// you need to use a Psr-3 Logger
$logger = new Logger('troba-test', [new StreamHandler(__DIR__ . '/troba-tests.log', Logger::DEBUG)]);

use troba\EQM\EQM;

// init the default connection
EQM::initialize([
    'dsn' => 'mysql:host=localhost;dbname=orm_test',
    'username' => 'root',
    'password' => 'root',
    EQM::RUN_MODE => EQM::DEV_MODE,
    EQM::LOGGER => $logger
]);

// init the second connection
EQM::initialize([
    'dsn' => 'mysql:host=localhost;dbname=orm_test2',
    'username' => 'root',
    'password' => 'root',
    EQM::RUN_MODE => EQM::DEV_MODE,
    EQM::CONVENTION_HANDLER => new \troba\EQM\ClassicConventionHandler(),
    EQM::LOGGER => $logger
], 'second');

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

