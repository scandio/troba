# Active record extension

troba allows to implement entity classes using the active record pattern.

http://en.wikipedia.org/wiki/Active_record_pattern

It's an extension and it's not part of the troba core. There are 5 traits that you can use.

* troba\Model\Queries
* troba\Model\Finders
* troba\Model\Persisters
* troba\Model\Getters
* troba\Model\Setters

You do not have to use them all. Use those ones you need and skip all others.

## Using Queries

```php
class Company
{
    use Queries;
}

$company = Company::query()->where('id = ?', 1)->one(); // retrieves the record  as a Company object with the id = 1

$result = Company::query()->all(); // retrieves all records as a ResultSet from table company

$result = Company::query() // inner join query
    ->innerJoin(Project::class, 'Company.id = Project.companyId')
    ->where('Project.name LIKE :name', ['name' => '%PRO%'])
    ->result();
```

## Using Finders

```php
class Company
{
    use Finders;
}

$company = Company::find(1); // retrieves the record  as a Company object with the id = 1

$result = Company::findAll(); // retrieves all records as a ResultSet from table company

$result = Company::findBy('name', 'Scandio GmbH'); // retrieves all records as a ResultSet
                                                   // from table company
                                                   //  where column name is equal to 'Scandio GmbH'

$result = Company::findByName('Scandio'); // the same as above using magic methods
```

## Using Persisters

```php
class Company
{
    use Persisters;
}

$company = new Company();
$company->name = 'Scandio GmbH';
$company->save();
```

The method `save()`recognizes wether the object needs to be inserted or updated
if there is a primary key with auto increment. if not it's not possible to use `save()`.
Use `insert()` and `update()` instead.

```php
class Company
{
    use Persisters;
}

$company = new Company();
$company->name = 'Scandio GmbH';
$company->insert();
$company->remark = 'Software & Consulting';
$company->update();
```

## Using Getters and Setters

t.b.d.
