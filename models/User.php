<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_HIDDEN = 1;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'created_at', 'updated_at'], 'required'],
            [[
                'status',
                'created_at',
                'updated_at',
            ], 'integer'],
            [[
                'username',
                'email',
            ], 'string', 'max' => 255],

            [['username'], 'unique'],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_HIDDEN]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username (login)'),
            'statusText' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return array
     */
    public static function getStatusTexts()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_DELETED => Yii::t('app', 'Deleted'),
            self::STATUS_HIDDEN => Yii::t('app', 'Hidden'),
        ];
    }

    /**
     * @return string
     */
    public function getStatusText()
    {
        $a = self::getStatusTexts();
        return isset($a[$this->status]) ? $a[$this->status] : $this->status;
    }
}
