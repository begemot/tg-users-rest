<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_channel_access".
 *
 * @property int $id
 * @property int $user_id
 * @property int $channel_id
 * @property string $chat_id
 * @property int|null $has_access
 * @property string|null $granted_at
 * @property string|null $created_at
 *
 * @property TelegramChannel $channel
 * @property TelegramUser $user
 */
class UserChannelAccess extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_channel_access';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['granted_at'], 'default', 'value' => null],
            [['has_access'], 'default', 'value' => 0],
            [['user_id', 'channel_id', 'chat_id'], 'required'],
            [['user_id', 'channel_id', 'has_access'], 'integer'],
            [['granted_at', 'created_at'], 'safe'],
            [['chat_id'], 'string', 'max' => 255],
            [['user_id', 'channel_id', 'chat_id'], 'unique', 'targetAttribute' => ['user_id', 'channel_id', 'chat_id']],
            [['channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => TelegramChannel::class, 'targetAttribute' => ['channel_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => TelegramUser::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'channel_id' => 'Channel ID',
            'chat_id' => 'Chat ID',
            'has_access' => 'Has Access',
            'granted_at' => 'Granted At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Channel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(TelegramChannel::class, ['id' => 'channel_id']);
    }

    /**
     * Gets query for [[TelegramUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(TelegramUser::class, ['id' => 'user_id']);
    }
}
