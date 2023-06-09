<?php

declare(strict_types=1);

namespace app\dto;

use yii\base\Model;

class LinkData extends Model
{
    public string $url = '';
    public ?string $expiredAt = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'url', 'validSchemes'=> ['http', 'https']],
            [['url'], 'required'],
            [['expiredAt'], 'date', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getExpiredAt(): ?string
    {
        return $this->expiredAt;
    }
}