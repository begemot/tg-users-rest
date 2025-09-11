<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telegram_channel".
 *
 * @property int $id
 * @property string $channel_name
 * @property string|null $description
 * @property int|null $is_active
 * @property string|null $created_at
 *
 * @property UserChannelAccess[] $userChannelAccesses
 * @property TelegramUser[] $users
 */
class TelegramChannel extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram_channel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['is_active'], 'default', 'value' => 1],
            [[ 'channel_name'], 'required'],
            [['description'], 'string'],
            [['is_active'], 'integer'],
            [['created_at'], 'safe'],
        
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_name' => 'Channel Name',
            'description' => 'Description',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[UserChannelAccesses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserChannelAccesses()
    {
        return $this->hasMany(UserChannelAccess::class, ['channel_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(TelegramUser::class, ['id' => 'user_id'])->viaTable('user_channel_access', ['channel_id' => 'id']);
    }

}
