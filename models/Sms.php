<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%sms}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $customer_id
 * @property integer $status
 * @property string $phone_from
 * @property string $message
 * @property string $ins_ts
 * @property integer $direction
 * @property string $phone_to
 * @property integer $type
 * @property string $formatted_message
 *
 * @property string $statusText
 * @property string $directionText
 *
 * @property Customer $customer
 * @property User $user
 */
class Sms extends \yii\db\ActiveRecord
{
    const DIRECTION_INCOMING = 0;
    const DIRECTION_OUTGOING = 1;

    // incoming
    const STATUS_NEW = 0;
    const STATUS_READ = 1;
    const STATUS_ANSWERED = 2;

    // outgoing
    const STATUS_DRAFT = 10;
    const STATUS_WAIT = 11;
    const STATUS_SENT = 12;
    const STATUS_DELIVERED = 13;
    const STATUS_FAILED = 14;
    const STATUS_SUCCESS = 13;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sms}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone_to', 'direction'], 'required'],
            [['user_id', 'customer_id', 'status', 'direction', 'applicant_id', 'type'], 'integer'],
            [['message'], 'string'],
            [['ins_ts'], 'safe'],
            [['phone_from', 'phone_to'], 'string', 'max' => 255],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'status' => Yii::t('app', 'Status'),
            'statusText' => Yii::t('app', 'Status'),
            'phone_from' => Yii::t('app', 'Phone From'),
            'phone_to' => Yii::t('app', 'Phone To'),
            'message' => Yii::t('app', 'Message'),
            'ins_ts' => Yii::t('app', 'Date'),
            'direction' => Yii::t('app', 'Direction'),
            'directionText' => Yii::t('app', 'Direction'),
            'user.fullname' => Yii::t('app', 'User'),
            'customer.name' => Yii::t('app', 'Client'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return array
     */
    public static function getStatusTexts()
    {
        return [
            self::STATUS_NEW => Yii::t('app', 'New'),
            self::STATUS_READ => Yii::t('app', 'Read'),
            self::STATUS_ANSWERED => Yii::t('app', 'Answered'),

            self::STATUS_DRAFT => Yii::t('app', 'Draft'),
            self::STATUS_WAIT => Yii::t('app', 'Wait'),
            self::STATUS_SENT => Yii::t('app', 'Sent'),
            self::STATUS_DELIVERED => Yii::t('app', 'Delivered'),
        ];
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function getStatusTextByValue($value)
    {
        return self::getStatusTexts()[$value] ?? $value;
    }

    /**
     * @return mixed|string
     */
    public function getStatusText()
    {
        return self::getStatusTextByValue($this->status);
    }

    /**
     * @return array
     */
    public static function getDirectionTexts()
    {
        return [
            self::DIRECTION_INCOMING => Yii::t('app', 'Incoming'),
            self::DIRECTION_OUTGOING => Yii::t('app', 'Outgoing'),
        ];
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function getDirectionTextByValue($value)
    {
        return self::getDirectionTexts()[$value] ?? $value;
    }

    /**
     * @return mixed|string
     */
    public function getDirectionText()
    {
        return self::getDirectionTextByValue($this->direction);
    }
}
