<?php

use app\fixtures\LinkFixture;
use Tuupola\Base62;
use yii\helpers\Url;

class TestLinkCest
{
    private Base62 $base62Converter;

    public function _before(FunctionalTester $I)
    {
        $this->base62Converter = new Base62();

        $I->haveFixtures([
            'link' => [
                'class' => LinkFixture::class,
                'dataFile' => codecept_data_dir() . 'links.php'
            ]
        ]);
    }
    public function openNotExistsLink(FunctionalTester $I)
    {
        $I->amOnRoute('link/view', ['hash' => 'sdfdasf']);

        $I->seePageNotFound();
        $I->seeResponseCodeIs(404);
    }

    public function openExistsLink(FunctionalTester $I)
    {
        $link = $I->grabFixture('link', 'link5535');

        $hash = $this->base62Converter->encodeInteger($link->id);

        $I->amOnRoute('link/view', ['hash' => $hash]);

//        $I->canSeeResponseCodeIs(301);


    }
}