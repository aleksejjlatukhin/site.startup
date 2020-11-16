<?php


namespace app\models\forms;


use app\models\BusinessModel;
use yii\base\Model;

class FormCreateBusinessModel extends Model
{

    public $partners;
    public $resources;
    public $relations;
    public $distribution_of_sales;
    public $cost;
    public $revenue;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['relations', 'distribution_of_sales', 'resources'], 'string', 'max' => 255],
            [['partners', 'cost', 'revenue'], 'string', 'max' => 1000],
            [['relations', 'partners', 'distribution_of_sales', 'resources', 'cost', 'revenue'], 'trim'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'relations' => 'Взаимоотношения с клиентами',
            'partners' => 'Ключевые партнеры',
            'distribution_of_sales' => 'Каналы коммуникации и сбыта',
            'resources' => 'Ключевые ресурсы',
            'cost' => 'Структура издержек',
            'revenue' => 'Потоки поступления доходов',
        ];
    }


    public function create ($confirm_mvp_id, $mvp_id, $gcp_id, $problem_id, $segment_id, $project_id){

        $model = new BusinessModel();

        $model->confirm_mvp_id = $confirm_mvp_id;
        $model->mvp_id = $mvp_id;
        $model->gcp_id = $gcp_id;
        $model->problem_id = $problem_id;
        $model->segment_id = $segment_id;
        $model->project_id = $project_id;
        $model->relations = $this->relations;
        $model->partners = $this->partners;
        $model->distribution_of_sales = $this->distribution_of_sales;
        $model->resources = $this->resources;
        $model->cost = $this->cost;
        $model->revenue = $this->revenue;

        return $model->save() ? $model : null;
    }
}