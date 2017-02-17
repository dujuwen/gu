<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GuChange1;

/**
 * GuChange1Search represents the model behind the search form about `app\models\GuChange1`.
 */
class GuChange1Search extends GuChange1
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'deal_count', 'deal_num', 'current_date', 'z_j_c'], 'integer'],
            [['code', 'current_date_', 'created_at'], 'safe'],
            [['yesterday', 'today', 'max', 'min', 'change_rate', 'amplitude', 'current', 'rate', 'sh_rate', 'sh_num', 'sz_rate', 'sz_num'], 'number'],
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
        $query = GuChange1::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'yesterday' => $this->yesterday,
            'today' => $this->today,
            'max' => $this->max,
            'min' => $this->min,
            'deal_count' => $this->deal_count,
            'deal_num' => $this->deal_num,
            'change_rate' => $this->change_rate,
            'amplitude' => $this->amplitude,
            'current_date' => $this->current_date,
            'current_date_' => $this->current_date_ ? $this->current_date_ : date('Y-m-d'),
            'z_j_c' => $this->z_j_c,
            'current' => $this->current,
            'rate' => $this->rate,
            'sh_rate' => $this->sh_rate,
            'sh_num' => $this->sh_num,
            'sz_rate' => $this->sz_rate,
            'sz_num' => $this->sz_num,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

        $query->orderBy('current_date desc');

        return $dataProvider;
    }
}
