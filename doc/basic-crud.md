# Basic CRUD with troba

Create, update and delete entities is easy. You need no model but you can define one.

## Create an entity without a model

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

## Using a model class (which is a better solution)

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

## Get one entity by its primary key

```php
$c = EQM::queryByPrimary('Company', 34);
```

EQM gets the primary key definition from the table meta data. If you have model classes
with namespaces you have to use them. The use statement have no effect.

```php
$c = EQM::queryByPrimary('Your\\Namespace\\Company', 34);
```

If you don't like use long class names you're allowed to use an instance instead.

```php
use \Your\Namespace\Company;

$c = EQM::queryByPrimary(new Company(), 34);
```

It's cool - isn't it?

## Update an entity

If you want to update an entity it's nearly the same as inserting it.

```php
$c = EQM::queryByPrimary(new Company, 34);
$c->remark = 'Another cool remark';
EQM::update($c);
```

## Delete an entity

To delete an entity you call the `EQM::delete()` method.

```php
$c = EQM::queryByPrimary(new Company(), 34);
EQM::delete($c);
```

That's all from the basic CRUD operations.
