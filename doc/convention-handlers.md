# Convention handlers

A convention handler has the task to match the class name of a model class
to a table name. There are three build in convention handlers.

* DefaultConventionHandler
* ClassicConventionHandler
* PrefixedConventionHandler

All are based on the `ConventionHandlerInterface`. So you can develop your
own conventions handlers.

The interface is very simple.

```php
public function tableName($className, $tableName = null);
```

## DefaultConventionHandler

If you do not set any handler in the config array the `DefaultConventionHandler
is the default.

It doesn't convert the classname. It assumes that the table name equals the
class name except a dedicated table name is given.

## ClassicConventionHandler

If you want to have lowercase table names with an underscore instead of a
camelCased syntax you need the ClassicConventionHandler.

* Class name `Company` results to `company` as table name
* Class name `ProjectContact` results to `project_contact` as table name

## PrefixedConventionHandler

The prefixed handler is requires another convention handler as a parameter. It
makes it possible to write data into a prefixed table.

```php
new PrefixedConventionHandler('wp_', new ClassicConventionHandler());
```

This example results the following table name for the given class names

* Class name `Company` results to `wp_company`
* Class name `ProjectContact` results to `wp_project_contact`

## Warning

The convention handler do not manipulate the column name just the table names.
