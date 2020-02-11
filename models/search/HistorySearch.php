<?php

namespace app\models\search;

use app\models\History;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * HistorySearch represents the model behind the search form about `app\models\History`.
 *
 * @property array $objects
 */
class HistorySearch extends History
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'customer_id',
                'objects',
                'user_id',
                'search',
                'department_ids',
                'date_from',
                'date_to',
                'denyObjects'
            ], 'safe'],
        ];

    }

    public function behaviors()
    {
        return [];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'user_id' => \Yii::t('app', 'Agents'),
            'objects' => \Yii::t('app', 'Types'),
            'search' => \Yii::t('app', 'Search'),
            'department_ids' => \Yii::t('app', 'Department'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = History::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'ins_ts' => SORT_DESC,
                'id' => SORT_DESC
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        $query->addSelect('history.*');
        $query->with([
            'customer',
            'user',
            'sms',
            'task',
            'call',
            'fax',
        ]);

        $query->andFilterWhere([
            'history.customer_id' => $this->customer_id,
            'history.user_id' => $this->user_id
        ]);

        return $dataProvider;
    }
}
