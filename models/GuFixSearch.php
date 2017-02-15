<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GuFix;

/**
 * GuFixSearch represents the model behind the search form about `app\models\GuFix`.
 */
class GuFixSearch extends GuFix
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'total', 'circulation', 'hand_num', 'left_num'], 'integer'],
            [['name', 'pingyin'], 'safe'],
            [['hand_rate'], 'number'],
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
        $query = GuFix::find();

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
            'type' => $this->type,
            'total' => $this->total,
            'circulation' => $this->circulation,
            'hand_rate' => $this->hand_rate,
            'hand_num' => $this->hand_num,
            'left_num' => $this->left_num,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'pingyin', $this->pingyin]);

        return $dataProvider;
    }
}
