<?php

declare(strict_types=1);

namespace app\repositories;

use app\models\Link;

interface LinkRepositoryInterface
{
    /**
     * Find link by id
     *
     * @param int $id
     *
     * @return Link|null
     */
    public function findById(int $id): ?Link;

    /**
     * Save link
     *
     * @param Link $link
     *
     * @return void
     */
    public function save(Link $link): void;
}