<?php

namespace app\widgets\HistoryList;

use app\models\search\HistorySearch;
use app\widgets\Export\Export;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii;

class HistoryList extends Widget
{
    public function run()
    {
        $model = new HistorySearch();

        return $this->render('main', [
            'model' => $model,
            'linkExport' => $this->getLinkExport(),
            'dataProvider' => $model->search(\Yii::$app->request->queryParams)
        ]);
    }

    /**
     * @return string
     */
    private function getLinkExport()
    {
        $params = \Yii::$app->getRequest()->getQueryParams();
        $params = ArrayHelper::merge([
            'exportType' => Export::FORMAT_CSV
        ], $params);
        $params[0] = 'site/export';

        return Url::to($params);
    }
}
