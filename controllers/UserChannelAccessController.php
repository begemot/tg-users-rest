<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\TelegramChannel;
use app\models\TelegramUser;
use app\models\UserChannelAccess;

class UserChannelAccessController extends ActiveController
{
    public $modelClass = UserChannelAccess::class;

    public function actionVerify()
    {
        $chatId = Yii::$app->request->getBodyParam('chat_id');
        $channelName = Yii::$app->request->getBodyParam('channel_name');

        if ($chatId === null || $channelName === null) {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'chat_id и channel_name обязательны'];
        }

        $channel = TelegramChannel::findOne(['channel_name' => $channelName]);
        if ($channel === null) {
            $channel = new TelegramChannel([
                'channel_id' => $channelName,
                'channel_name' => $channelName,
            ]);
            $channel->save();
        }

        $user = TelegramUser::findOne(['chat_id' => $chatId]);
        if ($user === null) {
            $user = new TelegramUser([
                'chat_id' => $chatId,
            ]);
            $user->save();
        }

        $access = UserChannelAccess::findOne([
            'user_id' => $user->id,
            'channel_id' => $channel->id,
            'chat_id' => $chatId,
        ]);

        if ($access === null) {
            $access = new UserChannelAccess([
                'user_id' => $user->id,
                'channel_id' => $channel->id,
                'chat_id' => $chatId,
                'has_access' => 0,
            ]);
            $access->save();
            return ['has_access' => false, 'message' => 'Доступ запрещён'];
        }

        return ['has_access' => (bool)$access->has_access];
    }
}
