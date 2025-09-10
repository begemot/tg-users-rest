<?php

namespace app\controllers;

use yii\rest\ActiveController;

class TelegramUserController extends ActiveController
{
    public $modelClass = \app\models\TelegramUser::class;
}
