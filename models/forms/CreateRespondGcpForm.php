<?php


namespace app\models\forms;

use app\models\ConfirmGcp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Projects;
use app\models\RespondsGcp;
use app\models\Segment;
use app\models\User;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class CreateRespondGcpForm extends Model
{

    public $name;
    public $confirm_gcp_id;

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
     * @return RespondsGcp
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function create ()
    {
        $confirmGcp = ConfirmGcp::findOne($this->confirm_gcp_id);
        $gcp = Gcp::findOne($confirmGcp->gcp_id);
        $problem = GenerationProblem::findOne($gcp->problem_id);
        $segment = Segment::findOne($gcp->segment_id);
        $project = Projects::findOne($gcp->project_id);
        $user = User::findOne($project->user_id);

        $model = new RespondsGcp();
        $model->confirm_gcp_id = $this->confirm_gcp_id;
        $model->name = $this->name;

        if ($model->save()) {
            // Добавление пустых ответов на вопросы для нового респондента
            $model->addAnswersForNewRespond();
            // Обновление данных подтверждения
            $confirmGcp->count_respond = $confirmGcp->count_respond + 1;
            $confirmGcp->save();
            // Удаление кэша формы создания
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id. '/segments/segment-'.$segment->id.
                '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/confirm/formCreateRespond';
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
        $models = RespondsGcp::findAll(['confirm_gcp_id' => $this->confirm_gcp_id]);

        foreach ($models as $item){

            if (mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }
}