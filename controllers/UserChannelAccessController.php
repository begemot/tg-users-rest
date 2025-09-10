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
        $channelId = Yii::$app->request->getBodyParam('chat_id');
        $userChatId = Yii::$app->request->getBodyParam('user_id');
        $channelName = Yii::$app->request->getBodyParam('channel_name');

        if ($channelId === null || $userChatId === null || $channelName === null) {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'chat_id, user_id и channel_name обязательны'];
        }

        $channel = TelegramChannel::findOne(['channel_id' => $channelId]);
        if ($channel === null) {
            $channel = new TelegramChannel([
                'channel_id' => $channelId,
                'channel_name' => $channelName,
            ]);
            $channel->save();
        }

        $user = TelegramUser::findOne(['chat_id' => $userChatId]);
        if ($user === null) {
            $user = new TelegramUser([
                'chat_id' => $userChatId,
            ]);
            $user->save();
        }

        $access = UserChannelAccess::findOne([
            'user_id' => $user->id,
            'channel_id' => $channel->id,
            'has_access' => 1,
        ]);

        return ['has_access' => $access !== null];
    }
}
