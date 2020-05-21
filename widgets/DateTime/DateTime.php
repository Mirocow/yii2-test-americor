<?php

namespace app\widgets\DateTime;

use Exception;
use yii\base\Widget;
use Yii;
use yii\helpers\Html;

class DateTime extends Widget
{
    public $dateTime;

    /**
     * @return string
     * @throws Exception
     */
    public function run()
    {
        return
            Html::tag('i', '', ['class' => "icon glyphicon glyphicon-time"]) . " " .
            Yii::$app->formatter->format($this->dateTime, 'relativeTime') . ' - ' .
            Yii::$app->formatter->asDatetime($this->dateTime, 'MM/dd/y (hh:mm a)');
    }
}
