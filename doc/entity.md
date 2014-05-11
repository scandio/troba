# Entity classes

It is not necessary in troba that you define special model classes. Every object is persistable.

## Using the `__table` property

The simplest way is that you use an object which has a property `__table`. It does't matter whether
the property is public, private or protected.

```php
$obj = new StdClass();

$obj->__table = 'my_database_table';

//...

EQM::insert($obj);
```

Another way is that you cast an associative array as object as shown in the follwing example

```php
$data = [
    '__table' => 'Company',
    'name' => 'Scandio GmbH',
    'remark' => 'Software & Consulting'
];

EQM::insert((object)$data);
```

The `__table` property is having the highest priority.

## Using classes

The better way to define you model is that you define a class that corresponds to your
database table. The naming convention is handled by the ConventionHandlerInterface.

First all you need is an empty class.

```php
class Company
{

}
```

EQM automatically assumes the right table name. In the example above the table is `Company`.
So your source code is getting much cleaner. Using the `Company` class in a typical CRUD process
looks like this.

```php
//insert
$c = new Company();
$c->name = 'Scandio GmbH';
$c->remark = 'Software & Consulting';
EQM::insert($c);

//read
$c = EQM::queryByPrimary('Company', 21);

//update
$c->remark = 'Software & IT-Consulting';
EQM::update($c);

//delete
EQM::delete($c);
```

If you use model classes with a naming convention you do not need to define the `__table` property.