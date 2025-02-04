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
     * @return array
     */
    public function rules()
    {
        return [
            [
                'id',
                'integer'
            ],
            [
                [
                    'status',
                    'domain_id',
                    'status',
                    'template.name',
                    'route',
                    'request_param',
                    'container_id',
                    'default_properties_json',
                    'access_domain',
                    'access_read',
                    'access_update',
                    'access_delete'
                ],
                'safe',
            ],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['template.name']);
    }

    /**
     * {@inheritdoc}
     *
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
        // join for status field
        $query->joinWith('translationsMeta');
        $query->joinWith('translations');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $tableName = self::getTableSchema()->name;

        $query->andFilterWhere([ $tableName.'.id' => $this->id])
            ->andFilterWhere(['LIKE', 'status', $this->status])
            ->andFilterWhere(['LIKE', 'domain_id', $this->domain_id])
            ->andFilterWhere(['LIKE', 'status', $this->status])
            ->andFilterWhere(['template.name' => $this->getAttribute('template.name')])
            ->andFilterWhere(['LIKE', 'route', $this->route])
            ->andFilterWhere(['LIKE', 'request_param', $this->request_param])
            ->andFilterWhere(['LIKE', 'container_id', $this->container_id])
            ->andFilterWhere(['LIKE', 'default_properties_json', $this->default_properties_json])
            ->andFilterWhere(['LIKE', $tableName.'.access_owner', $this->access_owner])
            ->andFilterWhere(['LIKE', $tableName.'.access_domain', $this->access_domain])
            ->andFilterWhere(['LIKE', $tableName.'.access_read', $this->access_read])
            ->andFilterWhere(['LIKE', $tableName.'.access_update', $this->access_update])
            ->andFilterWhere(['LIKE', $tableName.'.access_delete', $this->access_delete])
        ->groupBy($tableName.'.id');

        return $dataProvider;
    }
}
