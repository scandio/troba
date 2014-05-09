troba
=====

troba is a easy to use and extensible PHP (5.4) entity and query manager based on PDO.

## Set up troba

You can install troba via composer. Or you download it. Here's an example for a `composer.json`.
For further information about versions look at [https://packagist.org/packages/scandio/troba](https://packagist.org/packages/scandio/troba).

```php
{
    "require": {
        "scandio/troba": "dev-master",
    }
}
```

Here's an example:
```php
<?php

// own class loader
require_once '../troba/lib/troba/Util/ClassLoader.php';
$loader = new \troba\Util\ClassLoader('troba', '../troba/lib');
$loader->register();

// or composer's loader
require_once 'vendor/autoload.php';

use troba\EQM\EQM;

EQM::initialize([
    'dsn' => 'mysql:host=localhost;dbname=orm_test',
    'username' => 'root',
    'password' => 'root',
    EQM::RUN_MODE => EQM::DEV_MODE,
]);

/**
 * Assuming a database table Company with id, name, remark as fields
 */
class Company
{
}

$c = new Company();
$c->name = 'Scandio GmbH';
$c->remark = 'Software & Consulting';
EQM::insert($c);

$c = EQM::query(new Company())->one();

echo $c->name;
```

## Basic CRUD

Create, update and delete entities is easy. You need no model but you can define one.

### Create an entity without a model

Assuming a table like this one:

```sql
CREATE TABLE `Company` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(512) NOT NULL,
  `remark` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
```

You can create a php standard object and insert a record:

```php
$c = new StdClass();
$c->__table = 'Company';
$c->name = 'Scandio GmbH';
$c->remark = 'Software & Consulting';
EQM::insert($c);

var_dump($c);
```
The result is:

```php
class stdClass#5 (4) {
  public $__table =>
  string(7) "Company"
  public $name =>
  string(12) "Scandio GmbH"
  public $remark =>
  string(21) "Software & Consulting"
  public $id =>
  string(2) "34"
}
```

which returns the object including the auto incremented id.

### Using a model class (which is a better solution)

```php
class Company
{
}

$c = new Company();
$c->name = 'Scandio GmbH';
$c->remark = 'Software & Consulting';
EQM::insert($c);
```

In this case you do not need to define a property `__table` because the class name
tells the system the table name. It's possible to define you own convention handler
that results a specific table name for a class name.

### Get one entity by its primary key

```php
$c = EQM::queryOneByPrimary('Company', 34);
```

EQM gets the primary key definition from the table meta data. If you have model classes
with namespaces you have to use them. The use statement have no effect.

```php
$c = EQM::queryOneByPrimary('Your\\Namespace\\Company', 34);
```

If you don't like use long class names you're allowed to use an instance instead.

```php
use \Your\Namespace\Company;

$c = EQM::queryOneByPrimary(new Company(), 34);
```

It's cool - isn't it?

### Update an entity

If you want to update an entity it's nearly the same as inserting it.

```php
$c = EQM::queryOneByPrimary(new Company, 34);
$c->remark = 'Another cool remark';
EQM::update($c);
```

### Delete an entity

To delete an entity you call the `EQM::delete()` method.

```php
$c = EQM::queryOneByPrimary(new Company(), 34);
EQM::delete($c);
```

That's all from the basic CRUD operations.

## Querying entities

There are three possibilities to query entities.

* Query by array
* Query by object
* Native query

### Query by array

```php
$companies = EQM::queryByArray([
    'entity' => new Company(),
    'query' => 'name = :name',
    'params' => ['name' => 'Scandio GmbH']
]);

echo $companies->count() . PHP_EOL;

foreach ($companies as $company) {
    echo $company->name . ' ' . $company->remark . PHP_EOL;
}
```

The `$companies` variable does not contain all object. It's only the database cursor.
This is important for a efficient memory management. First in the `foreach()` loop the
fetches will be done.

If you want to fetch all entities in an array use the `all()` method.

```php
$companies = EQM::query([
    'entity' => new Company(),
    'query' => 'name = :name',
    'params' => ['name' => 'Scandio GmbH']
])->all();

foreach ($companies as $company) {
    echo $company->name . ' ' . $company->remark . PHP_EOL;
}
```

In this case `$companies` in an array.

### Query by object

Query by object creates an object of the Query class. Each method expect `result()`,
`all()`, `one()` returns the query object itself so chaining is possible.

```php
$company = EQM::query('Company')
    ->where('name = ?', 'Scandio GmbH')
    ->result()
    ->one();
```

Now `$company` contains the first entity for the given query.

If you want to query data without a association to a model class you do it this way.

```php
$result = EQM::query()->select('name, remark')
    ->from('Company')
    ->where('name = ?', 'Scandio GmbH')
    ->orderBy('id')
    ->result()
    ->all();
```

The result is an object (StdClass) containing all required properties.

### Native query

```php
$company = EQM::nativeQuery('Company', 'SELECT * FROM company WHERE name = ?', 'Scandio GmbH')->one();
```

This query is a pure SQL statement. The result is the same as above mentioned.

## License

Copyright (c) Scandio <http://https://github.com/scandio/>

This software is under the MIT license.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
