<?php

declare(strict_types=1);

namespace app\unit\models;

use app\models\Link;
use Codeception\Test\Unit;

class LinkTest extends Unit
{
    private Link $model;

    public function _before()
    {
        $this->model = new Link();
    }


}