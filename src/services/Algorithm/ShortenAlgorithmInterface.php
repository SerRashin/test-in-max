<?php

declare(strict_types=1);

namespace app\services\Algorithm;

interface ShortenAlgorithmInterface
{
    /**
     * Encode identifier to short link
     *
     * @param int $value
     *
     * @return string
     */
    public function encodeToShortLink(int $value): string;

    /**
     * Decode short link representation to identifier
     *
     * @param string $value
     *
     * @return int
     */
    public function decodeToIdentifier(string $value): int;
}