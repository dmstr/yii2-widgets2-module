<?php

namespace hrzg\widget\models\crud\search;

use hrzg\widget\models\crud\WidgetContentTranslation as WidgetTranslationModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Widget represents the model behind the search form about `hrzg\widget\models\crud\Widget`.
 */
class WidgetContentTranslation extends WidgetTranslationModel
{
    /**
     * {@inheritdoc}
     *
     * @return unknown
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            [
                [
                    'widget_content_id',
                    'language',
                    'access_owner',
                    'access_domain',
                    'access_read',
                    'access_update',
                    'access_delete',
                    'created_at',
                    'updated_at',
                ],
                'safe',
            ],
        ];
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
        $query = WidgetTranslationModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andWhere(['language' => \Yii::$app->language]);

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'widget_content_id', $this->widget_content_id])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'access_owner', $this->access_owner])
            ->andFilterWhere(['like', 'access_domain', $this->access_domain])
            ->andFilterWhere(['like', 'access_read', $this->access_read])
            ->andFilterWhere(['like', 'access_update', $this->access_update])
            ->andFilterWhere(['like', 'access_delete', $this->access_delete]);


        return $dataProvider;
    }
}
