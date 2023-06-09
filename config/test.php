<?php

require __DIR__ . '/di.php';
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__) . '/src',
    'runtimePath' => dirname(__DIR__) . '/runtime',
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'en-US',
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'KLWlU6IBhX058SOhtmgPZVsvLzLzSHLv',
        ],
        'db' => $db,
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'GET <hash:\w+>' => 'link/view',
                'POST api/links' => 'api/links/create',
                'GET api/links/<hash:\w+>' => 'api/links/view',
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
        ],
    ],
    'params' => $params,
];
