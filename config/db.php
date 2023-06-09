<?php

use yii\db\Connection;

$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: 5432;
$dbname = getenv('DB_NAME') ?: 'dbname';
$user = getenv('DB_USER') ?: 'username';
$password = getenv('DB_PASSWORD') ?: 'password';

return [
    'class' => Connection::class,
    'dsn' => "pgsql:host=${host};port=${port};dbname=${dbname};user=${user};password=${password}",
    'username' => $user,
    'password' => $password,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
