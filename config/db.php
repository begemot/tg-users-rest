<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=prod_mariadb;dbname=tg_users',
    'username' => 'root',
    'password' => '123123123',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
