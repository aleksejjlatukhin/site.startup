<?php


namespace app\models\forms;

use app\models\Interview;
use app\models\Projects;
use app\models\Respond;
use app\models\Segment;
use app\models\User;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class FormCreateConfirmSegment extends Model
{
    public $segment_id;
    public $count_respond;
    public $count_positive;
    public $greeting_interview;
    public $view_interview;
    public $reason_interview;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['segment_id', 'count_respond', 'count_positive', 'greeting_interview', 'view_interview', 'reason_interview'], 'required'],
            [['segment_id'], 'integer'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'max' => '100'],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'string', 'max' => '2000'],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'trim'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Количество респондентов, соответствующих сегменту',
            'greeting_interview' => 'Приветствие в начале встречи',
            'view_interview' => 'Информация о вас для респондентов',
            'reason_interview' => 'Причина и тема (что побудило) для проведения исследования',
        ];
    }


    /**
     * @return Interview
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function create ()
    {
        $segment = Segment::findOne($this->segment_id);
        $project = Projects::findOne($segment->project_id);
        $user = User::findOne($project->user_id);

        $model = new Interview();
        $model->segment_id = $this->segment_id;
        $model->count_respond = $this->count_respond;
        $model->count_positive = $this->count_positive;
        $model->greeting_interview = $this->greeting_interview;
        $model->view_interview = $this->view_interview;
        $model->reason_interview = $this->reason_interview;

        if ($model->save()) {
            //Создание респондентов по заданному значению count_respond
            $model->createRespond();
            //Вопросы, которые будут добавлены по-умолчанию
            $this->addListQuestions($model->id);
            //Удаление кэша формы создания подтверждения
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/confirm/formCreateConfirm';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось создать подтверждение сегмента');
    }


    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    private function addListQuestions ($id)
    {
        $model = Interview::findOne($id);

        if ($model) {
            $model->addQuestionDefault('Как и посредством какого инструмента / процесса вы справляетесь с задачей?');
            $model->addQuestionDefault('Что нравится / не нравится в текущем положении вещей?');
            $model->addQuestionDefault('Вас беспокоит данная ситуация?');
            $model->addQuestionDefault('Что вы пытались с этим сделать?');
            $model->addQuestionDefault('Что вы делали с этим в последний раз, какие шаги предпринимали?');
            $model->addQuestionDefault('Если ничего не делали, то почему?');
            $model->addQuestionDefault('Сколько денег / времени на это тратится сейчас?');
            $model->addQuestionDefault('Есть ли деньги на решение сложившейся ситуации сейчас?');
            $model->addQuestionDefault('Что влияет на решение о покупке продукта?');
            $model->addQuestionDefault('Как принимается решение о покупке?');

            return true;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось добавить вопросы для подтверждения сегмента');
    }
}