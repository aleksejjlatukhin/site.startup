<?php


namespace app\models\forms;

use app\models\ConfirmGcp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class FormCreateConfirmGcp extends Model
{

    public $gcp_id;
    public $count_respond;
    public $count_positive;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gcp_id', 'count_respond', 'count_positive'], 'required'],
            [['gcp_id'], 'integer'],
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
            'count_respond' => 'Количество респондентов, подтвердивших проблему',
            'count_positive' => 'Необходимое количество респондентов, подтверждающих ценностное предложение',
        ];
    }


    /**
     * @return ConfirmGcp
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function create()
    {
        $gcp = Gcp::findOne($this->gcp_id);
        $problem = GenerationProblem::findOne($gcp->problem_id);
        $segment = Segment::findOne($gcp->segment_id);
        $project = Projects::findOne($gcp->project_id);
        $user = User::findOne($project->user_id);

        $model = new ConfirmGcp();
        $model->gcp_id = $this->gcp_id;
        $model->count_respond = $this->count_respond;
        $model->count_positive = $this->count_positive;

        if ($model->save()) {
            //Создание респондентов для программы подтверждения ГЦП из респондентов подтвердивших проблему
            $model->createRespond();
            //Вопросы, которые будут добавлены по-умолчанию
            $this->addListQuestions($model->id);
            //Удаление кэша формы создания подтверждения
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
                '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/confirm/formCreateConfirm';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось создать подтверждение ценностного предложения');
    }


    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    private function addListQuestions ($id)
    {
        $model = ConfirmGcp::findOne($id);

        if ($model) {
            $model->addQuestionDefault('Во сколько обходится эта проблема?');
            $model->addQuestionDefault('Сколько сейчас платят?');
            $model->addQuestionDefault('Какой бюджет до этого выделяли?');
            $model->addQuestionDefault('Что еще пытались сделать?');
            $model->addQuestionDefault('Заплатили бы вы «X» рублей за продукт, который выполняет задачу «Y»?');
            $model->addQuestionDefault('Как вы решаете эту проблему сейчас?');
            $model->addQuestionDefault('Кто будет финансировать покупку?');
            $model->addQuestionDefault('С кем еще мне следует переговорить?');
            $model->addQuestionDefault('Решает ли ценностное предложенное вашу проблему?');
            $model->addQuestionDefault('Вы бы рассказали об этом ценностном предложении своим коллегам?');
            $model->addQuestionDefault('Вы бы попросили своего руководителя приобрести продукт, который реализует данное ценностное предложение?');

            return true;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось добавить вопросы для подтверждения ценностного предложения');
    }
}