<?php

namespace common\models\persons;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\persons\Persons;

/**
 * PersonsSearch represents the model behind the search form of `common\models\persons\Persons`.
 */
class PersonsSearch extends Persons
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nationalityID', 'jobTitleID', 'companyID', 'accountStatus'], 'integer'],
            [['nameAR', 'nameEN', 'gender', 'maritalStatus', 'birthday', 'workID', 'password', 'accessToken'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Persons::find();

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
            'birthday' => $this->birthday,
            'nationalityID' => $this->nationalityID,
            'jobTitleID' => $this->jobTitleID,
            'companyID' => $this->companyID,
            'accountStatus' => $this->accountStatus,
        ]);

        $query->andFilterWhere(['like', 'nameAR', $this->nameAR])
            ->andFilterWhere(['like', 'nameEN', $this->nameEN])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'maritalStatus', $this->maritalStatus])
            ->andFilterWhere(['like', 'workID', $this->workID])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'accessToken', $this->accessToken]);

        return $dataProvider;
    }
}
