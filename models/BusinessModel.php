<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;


class BusinessModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_model';
    }

    public function getProject ()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }

    public function getSegment ()
    {
        return $this->hasOne(Segment::class, ['id' => 'segment_id']);
    }

    public function getProblem ()
    {
        return $this->hasOne(GenerationProblem::class, ['id' => 'problem_id']);
    }

    public function getGcp ()
    {
        return $this->hasOne(Gcp::class, ['id' => 'gcp_id']);
    }

    public function getMvp ()
    {
        return $this->hasOne(Mvp::class, ['id' => 'mvp_id']);
    }

    public function getConfirmMvp()
    {
        return $this->hasOne(ConfirmMvp::class, ['id' => 'confirm_mvp_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_mvp_id', 'relations', 'partners', 'distribution_of_sales', 'resources', 'cost', 'revenue'], 'required'],
            [['confirm_mvp_id', 'project_id', 'segment_id', 'problem_id', 'gcp_id', 'mvp_id', 'created_at', 'updated_at'], 'integer'],
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
            'id' => 'ID',
            'confirm_mvp_id' => 'Confirm Mvp ID',
            'relations' => 'Взаимоотношения с клиентами',
            'partners' => 'Ключевые партнеры',
            'distribution_of_sales' => 'Каналы коммуникации и сбыта',
            'resources' => 'Ключевые ресурсы',
            'cost' => 'Структура издержек',
            'revenue' => 'Потоки поступления доходов',
        ];
    }


    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
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
