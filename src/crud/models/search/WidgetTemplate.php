<?php
/**
 * /app/src/../runtime/giiant/e0080b9d6ffa35acb85312bf99a557f2
 *
 * @package default
 */


namespace hrzg\widget\crud\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use hrzg\widget\crud\models\WidgetTemplate as WidgetTemplateModel;

/**
 * WidgetTemplate represents the model behind the search form about `hrzg\widget\crud\models\WidgetTemplate`.
 */
class WidgetTemplate extends WidgetTemplateModel
{

	/**
	 *
	 * @inheritdoc
	 * @return unknown
	 */
	public function rules() {
		return [
			[['id'], 'integer'],
			[['name', 'json_schema', 'editor_settings', 'form'], 'safe'],
		];
	}


	/**
	 *
	 * @inheritdoc
	 * @return unknown
	 */
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}


	/**
	 * Creates data provider instance with search query applied
	 *
	 *
	 * @param array   $params
	 * @return ActiveDataProvider
	 */
	public function search($params) {
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
		->andFilterWhere(['like', 'editor_settings', $this->editor_settings])
		->andFilterWhere(['like', 'form', $this->form]);

		return $dataProvider;
	}


}
