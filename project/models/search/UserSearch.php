<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends Model
{
    public $id;
    public $username;
    public $email;
    public $created_at;
    public $last_login;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id',], 'integer'],
            [['username', 'email',  'created_at', 'last_login'], 'safe'],
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
        $query = User::find();

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
        ]);

        if($this->created_at) {
            $query->andWhere([
                'between',
                'created_at',
                strtotime($this->created_at),
                strtotime($this->created_at . ' +1 day')
            ]);
        }

        if($this->last_login) {
            $query->andWhere([
                'between',
                'last_login',
                strtotime($this->last_login),
                strtotime($this->last_login .  ' 1 day')
            ]);
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);


        return $dataProvider;
    }
}
