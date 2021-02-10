<?php


namespace app\models\forms;

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

class FormCreateConfirmMvp extends Model
{

    public $mvp_id;
    public $count_respond;
    public $count_positive;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mvp_id', 'count_respond', 'count_positive'], 'required'],
            [['mvp_id'], 'integer'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'max' => '100'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'count_respond' => 'Количество респондентов, подтвердивших ценностное предложение',
            'count_positive' => 'Необходимое количество респондентов, подтверждающих продукт (MVP)',
        ];
    }


    /**
     * @return ConfirmMvp
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function create()
    {
        $mvp = Mvp::findOne($this->mvp_id);
        $gcp = Gcp::findOne($mvp->gcp_id);
        $problem = GenerationProblem::findOne($mvp->problem_id);
        $segment = Segment::findOne($mvp->segment_id);
        $project = Projects::findOne($mvp->project_id);
        $user = User::findOne($project->user_id);

        $model = new ConfirmMvp();
        $model->mvp_id = $this->mvp_id;
        $model->count_respond = $this->count_respond;
        $model->count_positive = $this->count_positive;

        if ($model->save()) {
            //Создание респондентов для программы подтверждения MVP из респондентов подтвердивших ГЦП
            $model->createRespond();
            //Вопросы, которые будут добавлены по-умолчанию
            $this->addListQuestions($model->id);
            //Удаление кэша формы создания подтверждения
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.
                '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/mvps/mvp-'.$mvp->id.'/confirm/formCreateConfirm';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось создать подтверждение продукта (MVP)');
    }


    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    private function addListQuestions ($id)
    {
        $model = ConfirmMvp::findOne($id);

        if ($model) {
            $model->addQuestionDefault('Что нравится в представленном MVP?');
            $model->addQuestionDefault('Что не нравится в представленном MVP?');
            $model->addQuestionDefault('Чем отличается ожидаемое решение от представленного?');
            $model->addQuestionDefault('Что бы Вы хотели сделать по другому?');
            $model->addQuestionDefault('Что показалось неудобным?');
            $model->addQuestionDefault('Вы готовы заплатить за такой продукт?');

            return true;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось добавить вопросы для подтверждения продукта (MVP)');
    }

}