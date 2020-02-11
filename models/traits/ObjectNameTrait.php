<?php
namespace app\models\traits;

use app\models\Call;
use app\models\Customer;
use app\models\Fax;
use app\models\Sms;
use app\models\Task;
use app\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

trait ObjectNameTrait
{
    public static $classes = [
        Customer::class,
        Sms::class,
        Task::class,
        Call::class,
        Fax::class,
        User::class,
    ];

    /**
     * @return array
     */
    public static function getObjectLabels()
    {
        return [
            Call::class => Yii::t('app', 'Calls'),
            Sms::class => Yii::t('app', 'SMS'),
            Task::class => Yii::t('app', 'Tasks'),
            Fax::class => Yii::t('app', 'Faxes'),
        ];
    }

    /**
     * @param $name
     * @param bool $throwException
     * @return mixed
     */
    public function getRelation($name, $throwException = true)
    {
        $getter = 'get' . $name;
        $class = self::getClassNameByRelation($name);

        if (!method_exists($this, $getter) && $class) {
            return $this->hasOne($class, ['id' => 'object_id']);
        }

        return parent::getRelation($name, $throwException);
    }

    /**
     * @param $className
     * @return mixed
     */
    public static function getObjectByTableClassName($className)
    {
        if (method_exists($className, 'tableName')) {
            return str_replace(['{', '}', '%'], '', $className::tableName());
        }

        return $className;
    }

    /**
     * @param $className
     * @return mixed
     */
    public static function getObjectByClassName($className)
    {
        return self::getObjectByTableClassName($className);
    }

    /**
     * @param ActiveRecord $model
     * @return string
     */
    public static function getObjectName($model)
    {
        return self::getObjectByClassName($model::className());
    }

    /**
     * @param $className
     * @return mixed
     */
    public static function getRelationByClassName($className)
    {
        return self::getObjectByClassName($className);
    }

    /**
     * @param $object
     * @return mixed
     */
    public static function getClassNameByObject($object)
    {
        foreach (self::$classes as $class) {
            if (self::getObjectByClassName($class) == $object) {
                return $class;
            }
        }
    }

    /**
     * @param $relation
     * @return mixed
     */
    public static function getClassNameByRelation($relation)
    {
        foreach (self::$classes as $class) {
            if (self::getRelationByClassName($class) == $relation) {
                return $class;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getObjectClassName()
    {
        return self::getClassNameByObject($this->object);
    }

    /**
     * @param $object
     * @return mixed
     */
    public static function getObjectText($object)
    {
        $a = self::getObjectLabels();
        return isset($a[$object]) ? $a[$object] : ucfirst($object);
    }

    /**
     * @param $className
     * @return bool
     */
    public function getIsObject($className)
    {
        return $this->object == self::getObjectByClassName($className);
    }

    /**
     * @return ActiveQuery
     */
    public function getObjectModel()
    {
        return $this->hasOne($this->getObjectClassName(), ['id' => 'object_id']);
    }

    /**
     * @param $objectClasses
     * @return array
     */
    public static function getObjectTextsByClass($objectClasses)
    {
        $labels = self::getObjectLabels();

        $result = [];
        foreach ($objectClasses as $className) {
            $result[self::getObjectByClassName($className)] = $labels[$className] ?? self::getObjectByClassName($className);
        }

        return $result;
    }
}