<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $system_user_id
 * @property string|null $username
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int $is_active
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property UserChannelAccess[] $userChannelAccesses
 * @property TelegramChannel[] $channels
 */
class TelegramUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
      
            [['is_active'], 'default', 'value' => 0],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['system_user_id', 'username', 'first_name', 'last_name'], 'string', 'max' => 255],
            [['system_user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'system_user_id' => 'user Telegram id',
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[UserChannelAccesses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserChannelAccesses()
    {
        return $this->hasMany(UserChannelAccess::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[TelegramChannel]] via junction table.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChannels()
    {
        return $this->hasMany(TelegramChannel::class, ['id' => 'channel_id'])->viaTable('user_channel_access', ['user_id' => 'id']);
    }
}
