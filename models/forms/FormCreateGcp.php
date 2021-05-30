<?php


namespace app\models\forms;

use app\models\ConfirmProblem;
use yii\base\ErrorException;
use yii\base\Model;
use app\models\User;
use app\models\Projects;
use app\models\Segment;
use app\models\GenerationProblem;
use app\models\Gcp;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class FormCreateGcp extends Model
{

    public $good;
    public $benefit;
    public $contrast;
    public $confirm_problem_id;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['good', 'benefit', 'contrast'], 'trim'],
            [['good', 'contrast'], 'string', 'max' => 255],
            [['benefit'], 'string', 'max' => 500],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'good' => 'Формулировка перспективного продукта',
            'benefit' => 'Какую выгоду дает использование данного продукта потребителю',
            'contrast' => 'По сравнению с каким продуктом заявлена выгода (с чем сравнивается)',
        ];
    }


    /**
     * @return Gcp
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create()
    {
        $last_model = Gcp::find()->where(['confirm_problem_id' => $this->confirm_problem_id])->orderBy(['id' => SORT_DESC])->one();
        $confirmProblem = ConfirmProblem::findOne($this->confirm_problem_id);
        $problem = GenerationProblem::findOne($confirmProblem->gps_id);
        $segment = Segment::findOne($problem->segment_id);
        $project = Projects::findOne($problem->project_id);
        $user = User::findOne($project->user_id);

        $gcp = new Gcp();
        $gcp->project_id = $project->id;
        $gcp->segment_id = $segment->id;
        $gcp->problem_id = $problem->id;
        $gcp->confirm_problem_id = $this->confirm_problem_id;
        $last_model_number = explode(' ',$last_model->title)[1];
        $gcp->title = 'ГЦП ' . ($last_model_number + 1);

        $gcp->description = 'Наш продукт ' . mb_strtolower($this->good) . ' ';
        $gcp->description .= 'помогает ' . mb_strtolower($segment->name) . ', ';
        $gcp->description .= 'который хочет удовлетворить проблему ' . mb_strtolower($problem->description) . ', ';
        $gcp->description .= 'избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, ' . mb_strtolower($this->benefit) . ', ';
        $gcp->description .= 'в отличии от ' . mb_strtolower($this->contrast) . '.';

        if ($gcp->save()){

            //Удаление кэша формы создания ГЦП
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
                '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/gcps/formCreate';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return $gcp;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить новое ценностное предложение');
    }

}