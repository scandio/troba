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


### License

Copyright (c) Scandio <http://https://github.com/scandio/>

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
