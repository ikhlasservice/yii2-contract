<?php

namespace backend\modules\contract\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\contract\models\Contract;

/**
 * ContractSearch represents the model behind the search form about `backend\modules\contract\models\Contract`.
 */
class ContractSearch extends Contract
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'credit_id', 'status', 'created_at', 'staff_id'], 'integer'],
            [['total'], 'number'],
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
        $query = Contract::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

         $query->joinWith('credit');
        if (Yii::$app->user->can('seller')) {
            $query->where([
                //'credit.status' => [1, 2,3],
                'credit.seller_id' => Yii::$app->user->id
            ]);
        } elseif (Yii::$app->user->can('staff')) {
            $query->where(['credit.status' => [3]]);
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
            'credit_id' => $this->credit_id,
            'status' => $this->status,
            'total' => $this->total,
            'created_at' => $this->created_at,
            'staff_id' => $this->staff_id,
        ]);

        return $dataProvider;
    }
}
