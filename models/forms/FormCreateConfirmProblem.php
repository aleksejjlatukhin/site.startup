<?php


namespace app\models\forms;

use app\models\ConfirmProblem;
use app\models\CreatorNewRespondsOnConfirmFirstStep;
use app\models\CreatorRespondsFromAgentsOnConfirmFirstStep;
use app\models\GenerationProblem;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\base\ErrorException;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class FormCreateConfirmProblem extends FormCreateConfirm
{

    public $need_consumer;


    /**
     * FormCreateConfirmProblem constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->_creatorResponds = new CreatorRespondsFromAgentsOnConfirmFirstStep();
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
        $problem = GenerationProblem::findOne($this->hypothesisId);
        $segment = Segment::findOne($problem->segmentId);
        $project = Projects::findOne($problem->projectId);
        $user = User::findOne($project->userId);

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
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
                '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/confirm/formCreateConfirm';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось создать подтверждение проблемы');
    }

}