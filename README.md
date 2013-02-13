troba
=====

troba is a easy to use and extensible PHP (5.4) entity and query manager based on PDO

## Set up troba

Here's an example:

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
         * Assuming a databse table Company with id, name, remark as fields
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
