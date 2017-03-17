<?php

namespace hrzg\widget\models\crud\search;

use hrzg\widget\models\crud\WidgetContent as WidgetModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Widget represents the model behind the search form about `hrzg\widget\models\crud\Widget`.
 */
class WidgetContent extends WidgetModel
{
    /**
     * {@inheritdoc}
     *
     * @return unknown
     */
    public function rules()
    {
        return [
            [['id', 'copied_from'], 'integer'],
            [
                [
                    'status',
                    'widget_template_id',
                    'default_properties_json',
                    'domain_id',
                    'container_id',
                    'rank',
                    'route',
                    'request_param',
                    'access_owner',
                    'access_domain',
                    'access_read',
                    'access_update',
                    'access_delete',
                    'created_at',
                    'updated_at',
                    'template.name',
                ],
                'safe',
            ],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(),['template.name']);
    }

    /**
     * {@inheritdoc}
     *
     * @return unknown
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
        $query = WidgetModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(
            [
                'template' => function (Query $query) {
                    $query->from(['template' => '{{%hrzg_widget_template}}']);
                }
            ]
        );

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'widget_template_id', $this->widget_template_id])
            ->andFilterWhere(['like', 'default_properties_json', $this->default_properties_json])
            ->andFilterWhere(['like', 'domain_id', $this->domain_id])
            ->andFilterWhere(['like', 'container_id', $this->container_id])
            ->andFilterWhere(['like', 'rank', $this->rank])
            ->andFilterWhere(['like', 'route', $this->route])
            ->andFilterWhere(['like', 'request_param', $this->request_param])
            ->andFilterWhere(['like', 'access_owner', $this->access_owner])
            ->andFilterWhere(['like', 'access_domain', $this->access_domain])
            ->andFilterWhere(['like', 'access_read', $this->access_read])
            ->andFilterWhere(['like', 'access_update', $this->access_update])
            ->andFilterWhere(['like', 'access_delete', $this->access_delete])
            ->andFilterWhere(['like', 'copied_from', $this->copied_from])
            ->andFilterWhere(['=', 'template.name', $this->getAttribute('template.name')]);

        return $dataProvider;
    }
}
