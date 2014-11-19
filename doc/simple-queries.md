# Querying entities

There are three possibilities to query entities.

* Query by array
* Query by object
* Native query

## Query by array

```php
$companies = EQM::queryByArray([
    'entity' => Company::class,
    'query' => 'name = :name',
    'params' => ['name' => 'Scandio GmbH']
]);

echo $companies->count() . PHP_EOL;

foreach ($companies as $company) {
    echo $company->name . ' ' . $company->remark . PHP_EOL;
}
```

The `$companies` variable does not contain all objects. It's only the database cursor.
This is important for efficient memory management. First in the `foreach()` loop the
fetches will be done.

If you want to fetch all entities in an array use the `all()` method.

```php
$companies = EQM::queryByArray([
    'entity' => Company::class,
    'query' => 'name = :name',
    'params' => ['name' => 'Scandio GmbH']
])->all();

foreach ($companies as $company) {
    echo $company->name . ' ' . $company->remark . PHP_EOL;
}
```

In this case `$companies` is a ResultSet and it's having array access.

## Query by object

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

## Native query

```php
$company = EQM::nativeQuery('Company', 'SELECT * FROM company WHERE name = ?', 'Scandio GmbH')->one();
```

This query is a pure SQL statement. The result is the same as above mentioned.