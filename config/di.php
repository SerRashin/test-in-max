<?php

$container = Yii::$container;

$container->set(
    'app\services\Algorithm\ShortenAlgorithmInterface',
    'app\services\Algorithm\Base62ShortenAlgorithm'
);

$container->set(
    'app\repositories\LinkRepositoryInterface',
    'app\repositories\LinkRepository'
);