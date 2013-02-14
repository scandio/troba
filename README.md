troba
=====

troba is a easy to use and extensible PHP (5.4) entity and query manager based on PDO

## Set up troba

Here's an example:
```php
<?php

require_once '../troba/lib/troba/Util/ClassLoader.php';
$loader = new \troba\Util\ClassLoader('troba', '../troba/lib');
$loader->register();

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

### Create without a model

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
