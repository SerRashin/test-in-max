<?php

declare(strict_types=1);

namespace app\repositories;

use app\models\Link;
use DateTime;

class LinkRepository implements LinkRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findById(int $id): ?Link
    {
        return Link::find()
            ->where(['id' => $id])
            ->andWhere(['>', 'expiredAt', (new DateTime())->format('Y-m-d H:i:s')]) // NOW() not working with tests
            ->one();
    }

    /**
     * @inheritDoc
     */
    public function save(Link $link): void
    {
        $link->save();
        $link->refresh();
    }
}