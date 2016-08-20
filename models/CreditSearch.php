<?php

namespace ikhlas\contract\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ikhlas\contract\models\Credit;

/**
 * CreditSearch represents the model behind the search form about `ikhlas\contract\models\Credit`.
 */
class CreditSearch extends Credit {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'customer_id', 'status', 'period', 'created_at', 'reserved_at', 'seller_id', 'staff_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Credit::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->where(['!=', 'status', 0]);
        if (Yii::$app->user->can('seller')) {
            $query->andWhere([
                'credit.seller_id' => Yii::$app->user->id
            ]);
        } elseif (Yii::$app->user->can('staff')) {
            //$query->andWhere(['credit.status' => [3]]);
        } elseif (Yii::$app->user->can('finance')) {
            $query->andWhere(['credit.status' => [3]]);
        }


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'status' => $this->status,
            'period' => $this->period,
            'created_at' => $this->created_at,
            'reserved_at' => $this->reserved_at,
            'seller_id' => $this->seller_id,
            'staff_id' => $this->staff_id,
        ]);

        return $dataProvider;
    }

}
