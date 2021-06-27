<?php


namespace app\models\forms;

use app\models\ConfirmMvp;
use app\models\CreatorNewRespondsOnConfirmFirstStep;
use app\models\CreatorRespondsFromAgentsOnConfirmFirstStep;
use app\models\Mvps;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;

class FormCreateConfirmMvp extends FormCreateConfirm
{


    /**
     * FormCreateConfirmMvp constructor.
     * @param Mvps $hypothesis
     * @param array $config
     */
    public function __construct(Mvps $hypothesis, $config = [])
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
     * @param Mvps $hypothesis
     * @return string
     */
    public static function getCachePath(Mvps $hypothesis)
    {
        $gcp = $hypothesis->gcp;
        $problem = $hypothesis->problem;
        $segment = $hypothesis->segment;
        $project = $hypothesis->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.
            '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/mvps/mvp-'.$hypothesis->id.'/confirm/formCreateConfirm/';

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
            $this->_cacheManager->deleteCache($this->cachePath);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось создать подтверждение продукта (MVP)');
    }

}