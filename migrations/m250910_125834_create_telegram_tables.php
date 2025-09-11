<?php

use yii\db\Migration;

class m250910_125834_create_telegram_tables extends Migration
{

public function safeUp()
{
    // Таблица пользователей (чат-ID)
    $this->createTable('user', [
        'id' => $this->primaryKey(),
        'system_user_id' => $this->string(255)->notNull()->unique(),
        'username' => $this->string(255),
        'first_name' => $this->string(255),
        'last_name' => $this->string(255),
        'is_active' => $this->boolean()->defaultValue(false),
        'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
    ]);

    // Таблица телеграм каналов
    $this->createTable('telegram_channel', [
        'id' => $this->primaryKey(),
        'channel_name' => $this->string(255)->notNull(),
        'description' => $this->text(),
        'is_active' => $this->boolean()->defaultValue(true),
        'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
    ]);

    // Таблица доступа пользователей к каналам
    $this->createTable('user_channel_access', [
        'id' => $this->primaryKey(),
        'user_id' => $this->integer()->notNull(),
        'channel_id' => $this->integer()->notNull(),
        'chat_id' => $this->string(255)->notNull(),
        'has_access' => $this->boolean()->defaultValue(false),
        'granted_at' => $this->timestamp(),
        'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
    ]);

    $this->addForeignKey(
        'fk_user_channel_access_user',
        'user_channel_access',
        'user_id',
        'user',
        'id',
        'CASCADE',
        'CASCADE'
    );

    $this->addForeignKey(
        'fk_user_channel_access_channel',
        'user_channel_access',
        'channel_id',
        'telegram_channel',
        'id',
        'CASCADE',
        'CASCADE'
    );

    $this->createIndex(
        'idx_user_channel_chat_unique',
        'user_channel_access',
        ['user_id', 'channel_id', 'chat_id'],
        true
    );
}

public function safeDown()
{
    $this->dropTable('user_channel_access');
    $this->dropTable('telegram_channel');
    $this->dropTable('user');
}
}
