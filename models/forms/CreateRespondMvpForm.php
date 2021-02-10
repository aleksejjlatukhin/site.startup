<?php


namespace app\models\forms;

use app\models\ConfirmMvp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Mvp;
use app\models\Projects;
use app\models\RespondsMvp;
use app\models\Segment;
use app\models\User;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class CreateRespondMvpForm extends Model
{

    public $name;
    public $confirm_mvp_id;

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
     * @return RespondsMvp
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function create ()
    {
        $confirmMvp = ConfirmMvp::findOne($this->confirm_mvp_id);
        $mvp = Mvp::findOne($confirmMvp->mvp_id);
        $gcp = Gcp::findOne($mvp->gcp_id);
        $problem = GenerationProblem::findOne($mvp->problem_id);
        $segment = Segment::findOne($mvp->segment_id);
        $project = Projects::findOne($mvp->project_id);
        $user = User::findOne($project->user_id);

        $model = new RespondsMvp();
        $model->confirm_mvp_id = $this->confirm_mvp_id;
        $model->name = $this->name;

        if ($model->save()) {
            // Добавление пустых ответов на вопросы для нового респондента
            $model->addAnswersForNewRespond();
            // Обновление данных подтверждения
            $confirmMvp->count_respond = $confirmMvp->count_respond + 1;
            $confirmMvp->save();
            // Удаление кэша формы создания
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id. '/segments/segment-'.$segment->id.
                '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/mvps/mvp-'.$mvp->id.'/confirm/formCreateRespond';
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
        $models = RespondsMvp::findAll(['confirm_mvp_id' => $this->confirm_mvp_id]);

        foreach ($models as $item){

            if (mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }
}