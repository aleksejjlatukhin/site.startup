<?php


namespace app\models\forms;


use app\models\BusinessModel;
use app\models\ConfirmMvp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Mvp;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class FormCreateBusinessModel extends Model
{

    public $partners;
    public $resources;
    public $relations;
    public $distribution_of_sales;
    public $cost;
    public $revenue;
    public $confirm_mvp_id;

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


    /**
     * @return BusinessModel
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function create (){

        $confirmMvp = ConfirmMvp::findOne($this->confirm_mvp_id);
        $mvp = Mvp::findOne($confirmMvp->mvp_id);
        $gcp = Gcp::findOne($mvp->gcp_id);
        $problem = GenerationProblem::findOne($mvp->problem_id);
        $segment = Segment::findOne($mvp->segment_id);
        $project = Projects::findOne($mvp->project_id);
        $user = User::findOne($project->user_id);

        $model = new BusinessModel();
        $model->confirm_mvp_id = $this->confirm_mvp_id;
        $model->mvp_id = $mvp->id;
        $model->gcp_id = $gcp->id;
        $model->problem_id = $problem->id;
        $model->segment_id = $segment->id;
        $model->project_id = $project->id;
        $model->relations = $this->relations;
        $model->partners = $this->partners;
        $model->distribution_of_sales = $this->distribution_of_sales;
        $model->resources = $this->resources;
        $model->cost = $this->cost;
        $model->revenue = $this->revenue;

        if ($model->save()){

            //Удаление кэша формы создания бизнес-модели
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.
                '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/mvps/mvp-'.$mvp->id.'/business-model/formCreate';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить бизнес-модель');
    }
}