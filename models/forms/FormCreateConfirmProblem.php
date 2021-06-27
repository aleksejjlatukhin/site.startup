<?php


namespace app\models\forms;

use app\models\ConfirmProblem;
use app\models\CreatorNewRespondsOnConfirmFirstStep;
use app\models\CreatorRespondsFromAgentsOnConfirmFirstStep;
use app\models\Problems;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;

class FormCreateConfirmProblem extends FormCreateConfirm
{

    public $need_consumer;


    /**
     * FormCreateConfirmProblem constructor.
     * @param Problems $hypothesis
     * @param array $config
     */
    public function __construct(Problems $hypothesis, $config = [])
    {
        $this->_creatorResponds = new CreatorRespondsFromAgentsOnConfirmFirstStep();
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
     * @param Problems $hypothesis
     * @return string
     */
    public static function getCachePath(Problems $hypothesis)
    {
        $segment = $hypothesis->segment;
        $project = $hypothesis->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
            '/segments/segment-'.$segment->id.'/problems/problem-'.$hypothesis->id.'/confirm/formCreateConfirm/';
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
            [['hypothesis_id', 'count_respond', 'count_positive', 'need_consumer'], 'required'],
            [['hypothesis_id'], 'integer'],
            [['need_consumer'], 'trim'],
            [['need_consumer'], 'string', 'max' => 255],
            [['count_respond', 'count_positive', 'add_count_respond'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['count_respond', 'count_positive', 'add_count_respond'], 'integer', 'integerOnly' => TRUE, 'max' => '100'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'count_respond' => 'Количество респондентов',
            'add_count_respond' => 'Добавить новых респондентов',
            'count_positive' => 'Необходимое количество респондентов, подтверждающих проблему',
            'need_consumer' => 'Потребность потребителя',
        ];
    }


    /**
     * @return ConfirmProblem
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create()
    {
        $model = new ConfirmProblem();
        $model->setProblemId($this->hypothesisId);
        $model->setNeedConsumer($this->need_consumer);
        $model->setCountRespond(array_sum([$this->count_respond, $this->add_count_respond]));
        $model->setCountPositive($this->count_positive);

        if ($model->save()) {
            // Создание респондентов для программы подтверждения ГПС из представителей сегмента
            $this->_creatorResponds->create($model, $this);
            // Добавление новых респондентов для программы подтверждения ГПС
            if ($this->add_count_respond) $this->_creatorNewResponds->create($model, $this);
            // Удаление кэша формы создания подтверждения
            $this->_cacheManager->deleteCache($this->cachePath);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось создать подтверждение проблемы');
    }

}