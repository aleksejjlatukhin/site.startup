<?php


namespace app\models\forms;

use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\base\ErrorException;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class FormCreateProblem extends Model
{

    public $description;
    public $action_to_check;
    public $result_metric;
    public $interview_id;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'action_to_check', 'result_metric'], 'trim'],
            [['description', 'action_to_check', 'result_metric'], 'string', 'max' => 2000],
            [['interview_id'], 'integer'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'description' => 'Описание гипотезы проблемы сегмента',
            'action_to_check' => 'Действие для проверки',
            'result_metric' => 'Метрика результата',
        ];
    }


    /**
     * @return GenerationProblem
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create()
    {
        $last_model = GenerationProblem::find()->where(['interview_id' => $this->interview_id])->orderBy(['id' => SORT_DESC])->one();
        $interview = Interview::findOne($this->interview_id);
        $segment = Segment::findOne($interview->segment_id);
        $project = Projects::findOne($segment->project_id);
        $user = User::findOne($project->user_id);

        $generationProblem = new GenerationProblem();
        $generationProblem->project_id = $project->id;
        $generationProblem->segment_id = $segment->id;
        $generationProblem->interview_id = $this->interview_id;
        $generationProblem->description = $this->description;
        $generationProblem->action_to_check = $this->action_to_check;
        $generationProblem->result_metric = $this->result_metric;
        $last_model_number = explode(' ',$last_model->title)[1];
        $generationProblem->title = 'ГПС ' . ($last_model_number + 1);

        if ($generationProblem->save()) {

            //Удаление кэша формы создания ГПС
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/problems/formCreate';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return $generationProblem;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить новую проблему');
    }

}