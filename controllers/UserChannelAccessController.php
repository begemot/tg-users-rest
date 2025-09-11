<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\TelegramChannel;
use app\models\TelegramUser;
use app\models\UserChannelAccess;
use Exception;

class UserChannelAccessController extends ActiveController
{
    public $modelClass = UserChannelAccess::class;

    public function actionVerify()
    {
        $chatId = Yii::$app->request->getBodyParam('chat_id');
        $userId = Yii::$app->request->getBodyParam('user_id'); // ID пользователя в Telegram
        $channelName = Yii::$app->request->getBodyParam('channel_name');
        $username = Yii::$app->request->getBodyParam('username');


        // Валидация входных параметров
        if ($chatId === null || $userId === null || $channelName === null) {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'chat_id, user_id и channel_name обязательны'];
        }

        // 1. Находим или создаем канал по channel_name
        $channel = TelegramChannel::findOne(['channel_name' => $channelName]);
        if ($channel === null) {
            $channel = new TelegramChannel([
                'channel_name' => $channelName,
                // Другие поля канала, если нужны
            ]);
            if (!$channel->save()) {
                Yii::$app->response->statusCode = 500;
                return ['error' => 'Ошибка создания канала', 'errors' => $channel->errors];
            }
        }

        // 2. Находим или создаем пользователя по user_id (ID Telegram)
        $user = TelegramUser::findOne(['system_user_id' => $userId]);
        if ($user === null) {
            $user = new TelegramUser([
                'system_user_id' => (string)$userId,
                'username'=>$username
                // Другие поля пользователя, если нужны
            ]);
            if (!$user->save()) {
                Yii::$app->response->statusCode = 500;
                return ['error' => 'Ошибка создания пользователя', 'errors' => $user->errors];
            }
        }

        // 3. Проверяем существование записи о доступе
        $access = UserChannelAccess::findOne([
            'user_id' => $user->id, // ID записи пользователя в БД
            'channel_id' => $channel->id, // ID записи канала в БД
            'chat_id' => (string)$chatId // Уникальный идентификатор переписки
        ]);

        // 4. Если записи нет - создаем новую с доступом 0
        if ($access === null) {
            $access = new UserChannelAccess([
                'user_id' => $user->id,
                'channel_id' => $channel->id,
                'chat_id' => (string)$chatId,
                'has_access' => 0
            ]);

            if (!$access->save()) {
                Yii::$app->response->statusCode = 500;
                return ['error' => 'Ошибка создания записи доступа', 'errors' => $access->errors];
            }

            return [
                'has_access' => false,
                'message' => 'Доступ запрещён',
                'access_created' => true
            ];
        }

        // 5. Если запись существует - возвращаем текущий статус доступа
        return [
            'has_access' => (bool)$access->has_access,
            'message' => $access->has_access ? 'Доступ разрешен' : 'Доступ запрещён'
        ];
    }
}
