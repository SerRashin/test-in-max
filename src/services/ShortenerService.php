<?php

declare(strict_types=1);

namespace app\services;

use app\services\Algorithm\ShortenAlgorithmInterface;

class ShortenerService
{
    private ShortenAlgorithmInterface $algorithm;

    /**
     * @param ShortenAlgorithmInterface $algorithm
     */
    public function __construct(ShortenAlgorithmInterface $algorithm) {
        $this->algorithm = $algorithm;
    }

    /**
     * Encode to short link
     *
     * @param int $value
     *
     * @return string
     */
    public function encodeToShortLink(int $value): string
    {
        return $this->algorithm->encodeToShortLink($value);
    }

    /**
     * Decode to identifier
     *
     * @param string $value
     *
     * @return int
     */
    public function decodeToIdentifier(string $value): int
    {
        return $this->algorithm->decodeToIdentifier($value);
    }
}