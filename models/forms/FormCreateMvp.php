<?php


namespace app\models\forms;

use app\models\ConfirmGcp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Mvp;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class FormCreateMvp extends Model
{

    public $description;
    public $confirm_gcp_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'trim'],
            [['description'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @return Mvp
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function create()
    {
        $last_model = Mvp::find()->where(['confirm_gcp_id' => $this->confirm_gcp_id])->orderBy(['id' => SORT_DESC])->one();
        $confirmGcp = ConfirmGcp::findOne($this->confirm_gcp_id);
        $gcp = Gcp::findOne($confirmGcp->gcp_id);
        $problem = GenerationProblem::findOne($gcp->problem_id);
        $segment = Segment::findOne($gcp->segment_id);
        $project = Projects::findOne($gcp->project_id);
        $user = User::findOne(['id' => $project->user_id]);

        $mvp = new Mvp();
        $mvp->project_id = $project->id;
        $mvp->segment_id = $segment->id;
        $mvp->problem_id = $problem->id;
        $mvp->gcp_id = $gcp->id;
        $mvp->confirm_gcp_id = $this->confirm_gcp_id;
        $mvp->description = $this->description;
        $last_model_number = explode(' ',$last_model->title)[1];
        $mvp->title = 'MVP ' . ($last_model_number + 1);

        if ($mvp->save()){

            //Удаление кэша формы создания MVP
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.
                '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/mvps/formCreate';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return $mvp;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить новый продукт (MVP)');
    }

}