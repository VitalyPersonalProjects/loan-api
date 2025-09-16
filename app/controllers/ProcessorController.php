<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use app\models\Request;

class ProcessorController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * GET /processor?delay=5
     */
    public function actionIndex($delay = 5)
    {
        // Преобразуем delay в int и проверяем
        $delay = (int)$delay;
        if ($delay < 0) $delay = 0;

        // Получаем все заявки со статусом pending
        $requests = Request::find()->where(['status' => 'pending'])->all();

        foreach ($requests as $request) {
            // Эмулируем задержку обработки
            sleep($delay);

            // Проверяем, есть ли уже одобренная заявка у этого пользователя
            $hasApproved = Request::find()
                ->where(['user_id' => $request->user_id, 'status' => 'approved'])
                ->exists();

            if ($hasApproved) {
                $request->status = 'declined';
            } else {
                // 10% шанс на одобрение
                $request->status = (rand(1, 100) <= 10) ? 'approved' : 'declined';
            }

            $request->save();
        }

        return ['result' => true];
    }
}
