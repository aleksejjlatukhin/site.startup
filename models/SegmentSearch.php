<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Segment;
use Yii;

/**
 * SegmentSearch represents the model behind the search form of `app\models\Segment`.
 */
class SegmentSearch extends Segment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'type_of_interaction_between_subjects', 'age_from', 'age_to', 'gender_consumer', 'education_of_consumer', 'income_from', 'income_to', 'quantity_from', 'quantity_to', 'market_volume', 'created_at', 'updated_at'], 'integer'],
            [['name', 'description', 'field_of_activity', 'sort_of_activity', 'specialization_of_activity', 'company_products', 'company_partner', 'add_info', 'creat_date', 'plan_gps', 'fact_gps', 'plan_ps', 'fact_ps', 'plan_dev_gcp', 'fact_dev_gcp', 'plan_gcp', 'fact_gcp', 'plan_dev_gmvp', 'fact_dev_gmvp', 'plan_gmvp', 'fact_gmvp'], 'safe'],
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
        $query = Segment::find()->where(['project_id' => Yii::$app->request->get('id')]);

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
            'project_id' => $this->project_id,
            'type_of_interaction_between_subjects' => $this->type_of_interaction_between_subjects,
            'age_from' => $this->age_from,
            'age_to' => $this->age_to,
            'gender_consumer' => $this->gender_consumer,
            'education_of_consumer' => $this->education_of_consumer,
            'income_from' => $this->income_from,
            'income_to' => $this->income_to,
            'quantity_from' => $this->quantity_from,
            'quantity_to' => $this->quantity_to,
            'market_volume' => $this->market_volume,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'creat_date' => $this->creat_date,
            'plan_gps' => $this->plan_gps,
            'fact_gps' => $this->fact_gps,
            'plan_ps' => $this->plan_ps,
            'fact_ps' => $this->fact_ps,
            'plan_dev_gcp' => $this->plan_dev_gcp,
            'fact_dev_gcp' => $this->fact_dev_gcp,
            'plan_gcp' => $this->plan_gcp,
            'fact_gcp' => $this->fact_gcp,
            'plan_dev_gmvp' => $this->plan_dev_gmvp,
            'fact_dev_gmvp' => $this->fact_dev_gmvp,
            'plan_gmvp' => $this->plan_gmvp,
            'fact_gmvp' => $this->fact_gmvp,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'field_of_activity', $this->field_of_activity])
            ->andFilterWhere(['like', 'sort_of_activity', $this->sort_of_activity])
            ->andFilterWhere(['like', 'specialization_of_activity', $this->specialization_of_activity])
            ->andFilterWhere(['like', 'company_products', $this->company_products])
            ->andFilterWhere(['like', 'company_partner', $this->company_partner])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);

        return $dataProvider;
    }
}