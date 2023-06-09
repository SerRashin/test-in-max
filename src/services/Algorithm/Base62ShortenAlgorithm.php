<?php

declare(strict_types=1);

namespace app\services\Algorithm;

use Tuupola\Base62;

final class Base62ShortenAlgorithm implements ShortenAlgorithmInterface
{
    private Base62 $base62;

    /**
     * @param Base62 $base62
     */
    public function __construct(Base62 $base62)
    {
        $this->base62 = $base62;
    }

    /**
     * @inheritDoc
     */
    public function encodeToShortLink(int $value): string
    {
        return $this->base62->encodeInteger($value);
    }

    /**
     * @inheritDoc
     */
    public function decodeToIdentifier(string $value): int
    {
        return $this->base62->decodeInteger($value);
    }
}