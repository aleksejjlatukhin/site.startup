<?php


namespace app\models\forms;

use app\models\ConfirmProblem;
use app\models\GenerationProblem;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class FormCreateConfirmProblem extends Model
{
    public $gps_id;
    public $count_respond;
    public $count_positive;
    public $need_consumer;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gps_id', 'count_respond', 'count_positive', 'need_consumer'], 'required'],
            [['gps_id'], 'integer'],
            [['need_consumer'], 'trim'],
            [['need_consumer'], 'string', 'max' => 255],
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
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Необходимое количество позитивных ответов',
            'need_consumer' => 'Потребность потребителя',
        ];
    }


    /**
     * @return ConfirmProblem
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function create()
    {
        $problem = GenerationProblem::findOne($this->gps_id);
        $segment = Segment::findOne($problem->segment_id);
        $project = Projects::findOne($problem->project_id);
        $user = User::findOne($project->user_id);

        $model = new ConfirmProblem();
        $model->gps_id = $this->gps_id;
        $model->need_consumer = $this->need_consumer;
        $model->count_respond = $this->count_respond;
        $model->count_positive = $this->count_positive;

        if ($model->save()) {
            //Создание респондентов для программы подтверждения ГПС из представителей сегмента
            $model->createRespond();
            //Вопросы, которые будут добавлены по-умолчанию
            $this->addListQuestions($model->id);
            //Удаление кэша формы создания подтверждения
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
                '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/confirm/formCreateConfirm';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось создать подтверждение проблемы');
    }


    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    private function addListQuestions ($id)
    {
        $model = ConfirmProblem::findOne($id);

        if ($model) {
            $model->addQuestionDefault('Какими функциями должен обладать продукт вашей мечты?');
            $model->addQuestionDefault('Расскажите поподробнее, каков алгоритм вашей работы?');
            $model->addQuestionDefault('Почему вас это беспокоит?');
            $model->addQuestionDefault('Каковы последствия этой ситуации?');
            $model->addQuestionDefault('Расскажите поподробнее, что произошло в последний раз?');
            $model->addQuestionDefault('Что еще пытались сделать?');
            $model->addQuestionDefault('Кто будет финансировать покупку?');
            $model->addQuestionDefault('С кем еще мне следует переговорить?');
            $model->addQuestionDefault('Есть ли еще вопросы, которые мне следовало задать?');
            $model->addQuestionDefault('Пытались ли найти решение?');
            $model->addQuestionDefault('Эти решения оказались недостаточно эффективными?');
            $model->addQuestionDefault('Как справляются с задачей сейчас и сколько денег тратят?');
            $model->addQuestionDefault('Сколько времени это занимает?');
            $model->addQuestionDefault('Продемонстрировать как они выполняют работу или другую деятельность?');
            $model->addQuestionDefault('Что в этом нравится и что нет?');
            $model->addQuestionDefault('Какие еще инструменты и процессы пробовали пока не остановились на этом?');
            $model->addQuestionDefault('Ищут ли активно сейчас чем это можно заменить?');
            $model->addQuestionDefault('Если да, то в чем проблема?');
            $model->addQuestionDefault('Если не ищут, то почему?');
            $model->addQuestionDefault('На чем теряют деньги, используя текущие инструменты?');

            return true;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось добавить вопросы для подтверждения сегмента');
    }

}