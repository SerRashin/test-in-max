<?php

use yii\web\Application;

$availableEnvironments = [
    'prod',
    'dev',
    'test',
];

$env = getenv('APP_ENV') ?: 'prod';

// flip array and isset better than in_array
if (!in_array($env, $availableEnvironments)) {
    throw new RuntimeException('Environment not available');
}

if ('dev' === $env)
{
    defined('YII_DEBUG') or define('YII_DEBUG', true);
}

defined('YII_ENV') or define('YII_ENV', $env);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';


$config = require __DIR__ . '/../config/web.php';

if ('test' === $env)
{
    $config = require __DIR__ . '/../config/test.php';
}

(new Application($config))->run();
