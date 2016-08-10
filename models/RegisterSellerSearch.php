<?php

namespace ikhlas\seller\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ikhlas\seller\models\RegisterSeller;

/**
 * RegisterSellerSearch represents the model behind the search form about `ikhlas\seller\models\RegisterSeller`.
 */
class RegisterSellerSearch extends RegisterSeller
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'person_id', 'staff_id', 'send_at'], 'integer'],
            [['status', 'data', 'doc', 'doc_fully', 'doc_because', 'score', 'staff_receive', 'staff_date', 'class', 'receive_because'], 'safe'],
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
        $query = RegisterSeller::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        
        $query->where(['!=','status',0]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'person_id' => $this->person_id,
            'staff_id' => $this->staff_id,
            'staff_date' => $this->staff_date,
            'send_at' => $this->send_at,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'doc', $this->doc])
            ->andFilterWhere(['like', 'doc_fully', $this->doc_fully])
            ->andFilterWhere(['like', 'doc_because', $this->doc_because])
            ->andFilterWhere(['like', 'score', $this->score])
            ->andFilterWhere(['like', 'staff_receive', $this->staff_receive])
            ->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'receive_because', $this->receive_because]);

        return $dataProvider;
    }
}
