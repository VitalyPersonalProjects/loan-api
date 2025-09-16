<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use app\models\Request;

class RequestController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * POST /requests
     */
    public function actionCreate()
    {
        $data = json_decode(Yii::$app->request->getRawBody(), true);

        if (!isset($data['user_id'], $data['amount'], $data['term'])) {
            Yii::$app->response->statusCode = 400;
            return ['result' => false, 'error' => 'Missing required fields'];
        }

        $existing = Request::find()
            ->where(['user_id' => $data['user_id'], 'status' => 'approved'])
            ->exists();

        if ($existing) {
            Yii::$app->response->statusCode = 400;
            return ['result' => false, 'error' => 'User already has an approved request'];
        }

        $request = new Request();
        $request->user_id = (int)$data['user_id'];
        $request->amount = (int)$data['amount'];
        $request->term = (int)$data['term'];

        if ($request->save()) {
            Yii::$app->response->statusCode = 201;
            return ['result' => true, 'id' => $request->id];
        }

        Yii::$app->response->statusCode = 400;
        return ['result' => false, 'errors' => $request->errors];
    }
}