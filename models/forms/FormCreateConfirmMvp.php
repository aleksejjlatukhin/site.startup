<?php


namespace app\models\forms;

use app\models\ConfirmMvp;
use app\models\CreatorNewRespondsOnConfirmFirstStep;
use app\models\CreatorRespondsFromAgentsOnConfirmFirstStep;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Mvp;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\base\ErrorException;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class FormCreateConfirmMvp extends FormCreateConfirm
{

    /**
     * FormCreateConfirmMvp constructor.
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
            [['hypothesis_id', 'count_respond', 'count_positive'], 'required'],
            [['hypothesis_id'], 'integer'],
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
            'count_respond' => 'Количество респондентов, подтвердивших ценностное предложение',
            'count_positive' => 'Необходимое количество респондентов, подтверждающих продукт (MVP)',
        ];
    }


    /**
     * @return ConfirmMvp
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create()
    {
        $mvp = Mvp::findOne($this->hypothesisId);
        $gcp = Gcp::findOne($mvp->gcpId);
        $problem = GenerationProblem::findOne($mvp->problemId);
        $segment = Segment::findOne($mvp->segmentId);
        $project = Projects::findOne($mvp->projectId);
        $user = User::findOne($project->userId);

        $model = new ConfirmMvp();
        $model->setMvpId($this->hypothesis_id);
        $model->setCountRespond(array_sum([$this->count_respond, $this->add_count_respond]));
        $model->setCountPositive($this->count_positive);

        if ($model->save()) {
            //Создание респондентов для программы подтверждения MVP из респондентов подтвердивших ГЦП
            $this->_creatorResponds->create($model, $this);
            // Добавление новых респондентов для программы подтверждения MVP
            if ($this->add_count_respond) $this->_creatorNewResponds->create($model, $this);
            //Удаление кэша формы создания подтверждения
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.
                '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/mvps/mvp-'.$mvp->id.'/confirm/formCreateConfirm';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось создать подтверждение продукта (MVP)');
    }

}