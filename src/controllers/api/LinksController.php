<?php

declare(strict_types=1);

namespace app\controllers\api;

use app\dto\LinkData;
use app\models\Link;
use app\services\LinkService;
use app\views\api\LinkView;
use Codeception\Util\HttpCode;
use Swagger\Annotations as SWG;
use Yii;
use yii\base\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class LinksController extends Controller
{
    private LinkService $linkService;

    public function __construct(string $id, Module $module, LinkService $linkService)
    {
        $this->linkService = $linkService;

        parent::__construct($id, $module);

        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    /**
     * Create link action
     *
     * @SWG\Post(path="/links",
     *     tags={"Link"},
     *     summary="Create shorten link.",
     *     @SWG\Parameter(
     *       name="body",
     *       in="body",
     *       type="string",
     *       required=true,
     *         @SWG\Schema(
     *              required={"url"},
     *              @SWG\Property(property="url", type="string"),
     *              @SWG\Property(property="expiredAt", type="string")
     *         )
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "Link data",
     *         @SWG\Schema(
     *              @SWG\Property(property="hash", type="string"),
     *              @SWG\Property(property="url", type="string"),
     *              @SWG\Property(property="shortUrl", type="string"),
     *              @SWG\Property(property="expiredAt", type="string")
     *         )
     *     )
     * )
     *
     * @return Response
     */
    public function actionCreate(): Response
    {
        $post = Yii::$app->request->post();

        $linkData = new LinkData();
        $linkData->load($post, '');

        if (!$linkData->validate()) {
            $response = $this->asJson([
                'message' => 'Validation errors',
                'details' => $linkData->getErrors(),
            ]);

            $response->setStatusCode(400);

            return $response;
        }

        return $this->asJson(LinkView::create(
            $this->linkService->createLink($linkData)
        ));
    }

    /**
     * View link action
     *
     * @SWG\Get(
     *     path="/links/{hash}",
     *     tags={"Link"},
     *     summary="View link",
     *     @SWG\Parameter(
     *       name="hash",
     *       in="path",
     *       type="string",
     *       required=true,
     *       default=""
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "Link data",
     *         @SWG\Schema(
     *              @SWG\Property(property="hash", type="string"),
     *              @SWG\Property(property="url", type="string"),
     *              @SWG\Property(property="shortUrl", type="string"),
     *              @SWG\Property(property="expiredAt", type="string")
     *         )
     *     ),
     *     @SWG\Response(
     *         response = 404,
     *         description = "link not found",
     *     )
     * )
     *
     * @return Response
     */
    public function actionView(string $hash)
    {
        $link = $this->linkService->getLinkByHash($hash);
        if (!$link) {
            Yii::$app->response->setStatusCode(HttpCode::NOT_FOUND);

            return $this->asJson([
                'message' => 'Link not found',
            ]);
        }

        return $this->asJson(LinkView::create(
            $link
        ));
    }
}
