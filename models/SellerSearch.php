<?php

namespace ikhlas\seller\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ikhlas\seller\models\Seller;

/**
 * SellerSearch represents the model behind the search form about `ikhlas\seller\models\Seller`.
 */
class SellerSearch extends Seller
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'register_seller_id', 'person_id', 'staff_id', 'user_id'], 'integer'],
            [['status', 'receive_data'], 'safe'],
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
        $query = Seller::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'register_seller_id' => $this->register_seller_id,
            'person_id' => $this->person_id,
            'staff_id' => $this->staff_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'receive_data', $this->receive_data]);

        return $dataProvider;
    }
}
