<?php


namespace app\models\forms;

use app\models\ConfirmProblem;
use app\models\GenerationProblem;
use app\models\Projects;
use app\models\RespondsConfirm;
use app\models\Segment;
use app\models\User;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class CreateRespondConfirmForm extends Model
{
    public $name;
    public $confirm_problem_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'uniqueName'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Фамилия, имя, отчество',
        ];
    }

    /**
     * @return RespondsConfirm
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function create ()
    {
        $confirmProblem = ConfirmProblem::findOne($this->confirm_problem_id);
        $problem = GenerationProblem::findOne($confirmProblem->gps_id);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        $model = new RespondsConfirm();
        $model->confirm_problem_id = $this->confirm_problem_id;
        $model->name = $this->name;

        if ($model->save()) {
            // Добавление пустых ответов на вопросы для нового респондента
            $model->addAnswersForNewRespond();
            // Обновление данных подтверждения
            $confirmProblem->count_respond = $confirmProblem->count_respond + 1;
            $confirmProblem->save();
            // Удаление кэша формы создания
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
                '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/confirm/formCreateRespond';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось добавить нового респондента');
    }

    /**
     * @param $attr
     */
    public function uniqueName($attr)
    {
        $models = RespondsConfirm::findAll(['confirm_problem_id' => $this->confirm_problem_id]);

        foreach ($models as $item){

            if (mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }
}