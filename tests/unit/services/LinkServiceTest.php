<?php

namespace app\unit\services;

use app\dto\LinkData;
use app\models\Link;
use app\repositories\LinkRepositoryInterface;
use app\services\LinkService;
use app\services\ShortenerService;
use Codeception\Test\Unit;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;

class LinkServiceTest extends Unit
{
    private LinkService $service;

    /**
     * @var (LinkRepositoryInterface&MockObject)
     */
    private LinkRepositoryInterface $repository;

    /**
     * @var (ShortenerService&MockObject)
     */
    private ShortenerService $shortener;

    protected function setUp(): void
    {
        $this->shortener = $this->createMock(ShortenerService::class);
        $this->repository = $this->createMock(LinkRepositoryInterface::class);
        $this->service = new LinkService($this->repository, $this->shortener);

        parent::setUp();
    }

    public function testGetLinkIfLinkNotExists(): void
    {
        $hash = 'some hash';
        $someIdentifier = 123;

        $this->shortener->expects($this->once())
            ->method('decodeToIdentifier')
            ->with($this->identicalTo($hash))
            ->willReturn($someIdentifier);

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($this->identicalTo($someIdentifier))
            ->willReturn(null);

        $this->assertNull($this->service->getLinkByHash($hash));
    }

    public function testGetLinkByHash(): void
    {
        $this->assertEquals(1, 1);
        $hash = 'some hash';
        $someIdentifier = 123;

        $this->shortener->expects($this->once())
            ->method('decodeToIdentifier')
            ->with($this->identicalTo($hash))
            ->willReturn($someIdentifier);

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($this->identicalTo($someIdentifier))
            ->willReturn($link = $this->createMock(Link::class));

        $result = $this->service->getLinkByHash($hash);
        $this->assertEquals($link, $result);
    }

    public function testCreateLink()
    {
        $linkData = new LinkData();
        $linkData->load([
            'url' => 'some url'
        ]);

        $expectedLink = null;
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Link $link) use (&$expectedLink, $linkData): bool {
                $expirationDate = (new DateTime(
                    'now +' . Link::EXPIRATION_TIME
                ))->format('Y-m-d');

                $this->assertEquals($linkData->getUrl(), $link->getUrl());
                $this->assertEquals($expirationDate, $link->getExpiredAt()->format('Y-m-d'));

                $expectedLink = $link;

                return true;
            }));

        $link = $this->service->createLink($linkData);

        $this->assertEquals($expectedLink, $link);
    }
}
