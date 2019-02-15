<?php

namespace hrzg\widget\models\crud\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use hrzg\widget\models\crud\WidgetPage as WidgetPageModel;

/**
* WidgetPage represents the model behind the search form about `hrzg\widget\models\crud\WidgetPage`.
*/
class WidgetPage extends WidgetPageModel
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'access_owner'], 'integer'],
            [['view', 'access_domain', 'access_read', 'access_update', 'access_delete', 'created_at', 'updated_at'], 'safe'],
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
$query = WidgetPageModel::find();

$dataProvider = new ActiveDataProvider([
'query' => $query,
]);

$this->load($params);

if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
// $query->where('0=1');
return $dataProvider;
}

$query->andFilterWhere([
            'id' => $this->id,
            'access_owner' => $this->access_owner,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'view', $this->view])
            ->andFilterWhere(['like', 'access_domain', $this->access_domain])
            ->andFilterWhere(['like', 'access_read', $this->access_read])
            ->andFilterWhere(['like', 'access_update', $this->access_update])
            ->andFilterWhere(['like', 'access_delete', $this->access_delete]);

return $dataProvider;
}
}