<?php

declare(strict_types=1);

namespace app\views\api;

use app\models\Link;
use app\services\ShortenerService;
use Yii;
use yii\helpers\Url;

class LinkView
{
    /**
     * @param Link $link
     *
     * @return array<string, string>
     */
    public static function create(Link $link): array
    {
        $shortenService = Yii::$container->get('app\services\ShortenerService');
        $hash = $shortenService->encodeToShortLink($link->getId());

        return [
            'hash' => $hash,
            'url' => $link->getUrl(),
            'shortUrl' => Url::base('https') . '/' . $hash,
            'expiredAt' => $link->getExpiredAt()->format('Y-m-d H:i:s'),
        ];
    }
}