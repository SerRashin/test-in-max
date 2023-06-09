<?php

declare(strict_types=1);

namespace app\services;

use app\dto\LinkData;
use app\models\Link;
use app\repositories\LinkRepositoryInterface;

class LinkService
{
    private LinkRepositoryInterface $linkRepository;
    private ShortenerService $shortenService;

    public function __construct(
        LinkRepositoryInterface $linkRepository,
        ShortenerService $shortenService
    ) {
        $this->linkRepository = $linkRepository;
        $this->shortenService = $shortenService;
    }

    /**
     * Get link by hash
     *
     * @param string $hash
     *
     * @return Link|null
     */
    public function getLinkByHash(string $hash): ?Link
    {
        $id = $this->shortenService->decodeToIdentifier($hash);
        $link = $this->linkRepository->findById($id);

        if (null === $link) {
            return null;
        }

        $link->updateExpirationDate();

        $this->linkRepository->save($link);

        return $link;
    }

    /**
     * Request data
     *
     * @param LinkData $linkData Link DTO
     *
     * @return Link
     */
    public function createLink(LinkData $linkData): Link
    {
        $link = new Link;

        $link->changeUrl($linkData->getUrl());

        $expiredAt = $linkData->getExpiredAt();
        if (null !== $expiredAt) {
            $link->changeExpiredAt($expiredAt);
        }

        $this->linkRepository->save($link);

        return $link;
    }
}