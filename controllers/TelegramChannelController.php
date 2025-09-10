<?php

namespace app\controllers;

use yii\rest\ActiveController;

class TelegramChannelController extends ActiveController
{
    public $modelClass = \app\models\TelegramChannel::class;
}
