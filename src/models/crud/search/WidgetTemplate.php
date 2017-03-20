<?php

namespace hrzg\widget\models\crud\search;

use hrzg\widget\models\crud\WidgetTemplate as WidgetTemplateModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WidgetTemplate represents the model behind the search form about `hrzg\widget\models\crud\WidgetTemplate`.
 */
class WidgetTemplate extends WidgetTemplateModel
{
    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'json_schema', 'twig_template', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     *
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WidgetTemplateModel::find();

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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'json_schema', $this->json_schema])
            ->andFilterWhere(['like', 'twig_template', $this->twig_template]);

        $query->orderBy('name');

        return $dataProvider;
    }
}
