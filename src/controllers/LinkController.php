<?php

declare(strict_types=1);

namespace app\controllers;

use app\services\LinkService;
use yii\base\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class LinkController extends Controller
{
    private LinkService $linkService;

    public function __construct(string $id, Module $module, LinkService $linkService)
    {
        $this->linkService = $linkService;

        parent::__construct($id, $module);
    }

    /**
     * @param string $hash
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionView(string $hash): Response
    {
        $link = $this->linkService->getLinkByHash($hash);

        if (!$link) {
            throw new NotFoundHttpException();
        }

        return $this->redirect($link->getUrl(), 301);
    }
}