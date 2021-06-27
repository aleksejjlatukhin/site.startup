<?php


namespace app\models\forms;

use app\models\CreatorNewRespondsOnConfirmFirstStep;
use app\models\ConfirmSegment;
use app\models\Segments;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;

class FormCreateConfirmSegment extends FormCreateConfirm
{

    public $greeting_interview;
    public $view_interview;
    public $reason_interview;


    /**
     * FormCreateConfirmSegment constructor.
     * @param Segments $hypothesis
     * @param array $config
     */
    public function __construct(Segments $hypothesis, $config = [])
    {
        $this->_creatorNewResponds = new CreatorNewRespondsOnConfirmFirstStep();
        $this->_cacheManager = new CacheForm();
        $this->cachePath = self::getCachePath($hypothesis);
        $cacheName = 'formCreateConfirmCache';
        if ($cache = $this->_cacheManager->getCache($this->cachePath, $cacheName)) {
            $className = explode('\\', self::class)[3];
            foreach ($cache[$className] as $key => $value) $this[$key] = $value;
        }

        parent::__construct($config);
    }


    /**
     * Получить путь к кэшу формы
     * @param Segments $hypothesis
     * @return string
     */
    public static function getCachePath(Segments $hypothesis)
    {
        $project = $hypothesis->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$hypothesis->id.'/confirm/formCreateConfirm/';
        return $cachePath;
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
     * @return ConfirmSegment
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create ()
    {
        $model = new ConfirmSegment();
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
            // Удаление кэша формы создания
            $this->_cacheManager->deleteCache($this->cachePath);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось создать подтверждение сегмента');
    }

}