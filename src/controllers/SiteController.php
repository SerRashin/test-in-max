<?php

declare(strict_types=1);

namespace app\controllers;

use Swagger\Annotations as SWG;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * @SWG\Swagger(
 *     basePath="/api/",
 *     produces={"application/json"},
 *     consumes={"application/json"},
 *     @SWG\Info(version="1.0", title="Shorten API"),
 * )
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $url = Url::base('http') . '/docs/swagger.json';

        return [
            'index' => [
                'class' => 'yii2mod\swagger\SwaggerUIRenderer',
                'restUrl' => Url::to($url),
            ],
//            'json-schema' => [
//                'class' => 'yii2mod\swagger\OpenAPIRenderer',
//                'scanDir' => [
//                    Yii::getAlias('@app/controllers'),
//                    Yii::getAlias('@app/controllers/api'),
//                ],
//            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return 'todo';
    }

    /**
     * @return array
     */
    public function actionError(): array
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            Yii::error([
                'request_id' => Yii::$app->requestId->id,
                'exception' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
            ], 'response_data_error');
            return ['code' => $exception->getCode(), 'message' => $exception->getMessage()];
        }
        return [];
    }
}