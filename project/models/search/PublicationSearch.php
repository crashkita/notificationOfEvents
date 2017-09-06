<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Publication;

/**
 * PublicationSearch represents the model behind the search form of `app\models\Publication`.
 */
class PublicationSearch extends Model
{
    public $id;
    public $status_id;
    public $name;
    public $created_at;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id',  'status_id'], 'integer'],
            [['name', 'created_at'], 'safe'],
        ];
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
        $query = Publication::find();

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
            'status_id' => $this->status_id,
        ]);

        if($this->created_at) {
            list($minDate, $maxDate) = explode(' - ', $this->created_at);
            $query->andWhere([
                'between',
                'created_at',
                strtotime($minDate),
                strtotime($maxDate)
            ]);
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
