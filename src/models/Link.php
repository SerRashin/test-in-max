<?php

declare(strict_types=1);

namespace app\models;

use DateTime;
use yii\db\ActiveRecord;

/**
 * Link model
 */
class Link extends ActiveRecord
{
    public const EXPIRATION_TIME = '3 month';

    public function __construct() {
        $this->updateExpirationDate();

        parent::__construct();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set origin url
     *
     * @param string $url
     *
     * @return void
     */
    public function changeUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return DateTime
     */
    public function getExpiredAt(): DateTime
    {
        return new DateTime($this->expiredAt);
    }

    public function changeExpiredAt(string $date)
    {

        $this->expiredAt = (new DateTime(
            $date
        ))->format('Y-m-d H:i:s');
    }

    /**
     * @return void
     */
    public function updateExpirationDate(): void
    {
        $this->changeExpiredAt('now +' . self::EXPIRATION_TIME);
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['url', 'expiredAt'], 'required'],
            [['url'], 'url', 'validSchemes'=> ['http', 'https']],
            [['expiredAt'], 'date', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'links';
    }
}
