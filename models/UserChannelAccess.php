<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_channel_access".
 *
 * @property int $id
 * @property int $user_id
 * @property int $channel_id
 * @property int|null $has_access
 * @property string|null $granted_at
 * @property string|null $created_at
 *
 * @property TelegramChannel $channel
 * @property User $user
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
            [['user_id', 'channel_id'], 'required'],
            [['user_id', 'channel_id', 'has_access'], 'integer'],
            [['granted_at', 'created_at'], 'safe'],
            [['user_id', 'channel_id'], 'unique', 'targetAttribute' => ['user_id', 'channel_id']],
            [['channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => TelegramChannel::class, 'targetAttribute' => ['channel_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
