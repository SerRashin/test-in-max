<?php

declare(strict_types=1);

namespace app\api;

use ApiTester;
use app\fixtures\LinkFixture;
use Codeception\Util\HttpCode;
use FunctionalTester;
use Tuupola\Base62;
use yii\helpers\Url;

class LinkCest
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

    public function getNotExistsLink(ApiTester $I)
    {
        $I->sendGet('links/4324');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);

        $I->seeResponseContainsJson([
            'message' => 'Link not found'
        ]);
    }

    public function getExistsLink(ApiTester $I)
    {
        $link = $I->grabFixture('link', 'link1');

        $I->sendGet('links/1');

        $I->seeResponseCodeIs(HttpCode::OK);
        $hash = $this->base62Converter->encodeInteger($link->id);

        $I->seeResponseContainsJson([
            'hash' => $hash,
            'url' => $link->url,
            'shortUrl' => Url::base('https') . '/' . $hash,
        ]);
    }

    public function createLinkWithInvalidUrl(ApiTester $I)
    {
        $url = 'htt://some.url/path/some';

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('links', [
            'url' => $url,
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);

        $I->seeResponseContainsJson([
            'message' => 'Validation errors',
            'details' => [
                'url' => [
                    "Url is not a valid URL."
                ]
            ]
        ]);
    }

    public function createLinkWithInvalidExpirationDate(ApiTester $I)
    {
        $url = 'https://some.url/path/some';

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('links', [
            'url' => $url,
            'expiredAt' => 'ddasdsad',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);

        $I->seeResponseContainsJson([
            'message' => 'Validation errors',
            'details' => [
                'expiredAt' => [
                    "The format of Expired At is invalid."
                ]
            ]
        ]);
    }

    public function createLink(ApiTester $I)
    {
        $url = 'https://some.url/path/some';

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('links', [
            'url' => $url,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseContainsJson([
            'url' => $url,
        ]);

        $I->seeResponseMatchesJsonType([
            'hash' => 'string',
            'url' => 'string',
            'shortUrl' => 'string',
            'expiredAt' => 'string',
        ]);
    }
}
