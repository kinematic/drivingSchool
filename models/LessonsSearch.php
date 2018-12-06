<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Lessons;
use dosamigos\datepicker\DatePicker;
/**
 * LessonsSearch represents the model behind the search form of `app\models\Lessons`.
 */
class LessonsSearch extends Lessons
{
	public $dateBegin;
	public $dateEnd;
	public $typeid;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'instructorid', 'customerid', 'vehicleid', 'typeid'], 'integer'],
            [['datetime', 'comment', 'dateBegin', 'dateEnd'], 'safe'],
            [['duration'], 'number'],
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
        $query = Lessons::find();

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
            'instructorid' => $this->instructorid,
            'customerid' => $this->customerid,
			'vehicleid' => $this->vehicleid,
			'typeid' => $this->typeid,
        ]);

		$query->andFilterWhere(['>=', 'datetime', $this->dateBegin]);
        $query->andFilterWhere(['<=', 'datetime', $this->dateEnd]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);
        $query->orderBy('datetime');
        return $dataProvider;
    }
}
