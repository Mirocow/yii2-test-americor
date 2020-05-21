<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%task}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $customer_id
 * @property integer $status
 * @property string $title
 * @property string $text
 * @property string $due_date
 * @property integer $priority
 * @property string $ins_ts
 *
 * @property string $stateText
 * @property string $state
 * @property string $subTitle
 *
 * @property boolean $isOverdue
 * @property boolean $isDone
 *
 * @property Customer $customer
 * @property User $user
 *
 *
 * @property string $isInbox
 * @property string $statusText
 */
class Task extends ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_DONE = 1;
    const STATUS_CANCEL = 3;

    const STATE_INBOX = 'inbox';
    const STATE_DONE = 'done';
    const STATE_FUTURE = 'future';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%task}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'title'], 'required'],
            [['user_id', 'customer_id', 'status', 'priority'], 'integer'],
            [['text'], 'string'],
            [['title', 'object'], 'string', 'max' => 255],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'status' => Yii::t('app', 'Status'),
            'title' => Yii::t('app', 'Title'),
            'text' => Yii::t('app', 'Description'),
            'due_date' => Yii::t('app', 'Due Date'),
            'formatted_due_date' => Yii::t('app', 'Due Date'),
            'priority' => Yii::t('app', 'Priority'),
            'ins_ts' => Yii::t('app', 'Ins Ts'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return array
     */
    public static function getStatusTexts()
    {
        return [
            self::STATUS_NEW => Yii::t('app', 'New'),
            self::STATUS_DONE => Yii::t('app', 'Complete'),
            self::STATUS_CANCEL => Yii::t('app', 'Cancel'),
        ];
    }

    /**
     * @param $value
     * @return int|mixed
     */
    public function getStatusTextByValue($value)
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
    public static function getStateTexts()
    {
        return [
            self::STATE_INBOX => Yii::t('app', 'Inbox'),
            self::STATE_DONE => Yii::t('app', 'Done'),
            self::STATE_FUTURE => Yii::t('app', 'Future')
        ];
    }

    /**
     * @return mixed
     */
    public function getStateText()
    {
        return self::getStateTexts()[$this->state] ?? $this->state;
    }


    /**
     * @return bool
     */
    public function getIsOverdue()
    {
        return $this->status !== self::STATUS_DONE && strtotime($this->due_date) < time();
    }

    /**
     * @return bool
     */
    public function getIsDone()
    {
        return $this->status == self::STATUS_DONE;
    }
}
