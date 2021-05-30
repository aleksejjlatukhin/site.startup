<?php


namespace app\models\forms;

use app\models\CreatorNewRespondsOnConfirmFirstStep;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\base\ErrorException;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class FormCreateConfirmSegment extends FormCreateConfirm
{

    public $greeting_interview;
    public $view_interview;
    public $reason_interview;


    /**
     * FormCreateConfirmSegment constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->_creatorNewResponds = new CreatorNewRespondsOnConfirmFirstStep();

        parent::__construct($config);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function setHypothesisId($id)
    {
        return $this->hypothesis_id = $id;
    }


    /**
     * @param $count
     * @return mixed
     */
    public function setCountRespond($count)
    {
        return $this->count_respond = $count;
    }


    /**
     * @return mixed
     */
    public function getHypothesisId()
    {
        return $this->hypothesis_id;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hypothesis_id', 'count_respond', 'count_positive', 'greeting_interview', 'view_interview', 'reason_interview'], 'required'],
            [['hypothesis_id'], 'integer'],
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
     * @throws ErrorException
     */
    public function create ()
    {
        $segment = Segment::findOne($this->hypothesisId);
        $project = Projects::findOne($segment->projectId);
        $user = User::findOne($project->userId);

        $model = new Interview();
        $model->setSegmentId($this->hypothesisId);
        $model->setCountRespond($this->count_respond);
        $model->setCountPositive($this->count_positive);
        $model->setParams([
            'greeting_interview' => $this->greeting_interview,
            'view_interview' => $this->view_interview,
            'reason_interview' => $this->reason_interview
        ]);

        if ($model->save()) {
            //Создание респондентов по заданному значению count_respond
            $this->_creatorNewResponds->create($model, $this);
            //Удаление кэша формы создания подтверждения
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/confirm/formCreateConfirm';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось создать подтверждение сегмента');
    }

}