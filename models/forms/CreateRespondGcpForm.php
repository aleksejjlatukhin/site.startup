<?php


namespace app\models\forms;

use app\models\ConfirmGcp;
use app\models\CreatorAnswersForNewRespond;
use app\models\Gcps;
use app\models\Problems;
use app\models\interfaces\ConfirmationInterface;
use app\models\Projects;
use app\models\RespondsGcp;
use app\models\Segments;
use app\models\User;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;

class CreateRespondGcpForm extends FormCreateRespondent
{


    /**
     * CreateRespondGcpForm constructor.
     * @param ConfirmGcp $confirm
     * @param array $config
     */
    public function __construct(ConfirmGcp $confirm, $config = [])
    {
        $this->_creatorAnswers = new CreatorAnswersForNewRespond();
        $this->_cacheManager = new CacheForm();
        $this->cachePath = self::getCachePath($confirm);
        $cacheName = 'formCreateRespondCache';
        if ($cache = $this->_cacheManager->getCache($this->cachePath, $cacheName)) {
            $className = explode('\\', self::class)[3];
            foreach ($cache[$className] as $key => $value) $this[$key] = $value;
        }

        parent::__construct($config);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function setConfirmId($id)
    {
        return $this->confirm_id = $id;
    }


    /**
     * @return mixed
     */
    public function getConfirmId()
    {
        return $this->confirm_id;
    }


    /**
     * @param $name
     * @return mixed
     */
    public function setName($name)
    {
        return $this->name = $name;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Получить путь к кэшу формы
     * @param ConfirmationInterface $confirm
     * @return string
     */
    public static function getCachePath(ConfirmationInterface $confirm)
    {
        $gcp = Gcps::findOne($confirm->gcpId);
        $problem = Problems::findOne($gcp->problemId);
        $segment = Segments::findOne($gcp->segmentId);
        $project = Projects::findOne($gcp->projectId);
        $user = User::findOne($project->userId);
        $cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id. '/segments/segment-'.$segment->id.
            '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/confirm/formCreateRespond/';
        return $cachePath;
    }


    /**
     * @return RespondsGcp
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create ()
    {
        $model = new RespondsGcp();
        $model->setConfirmId($this->confirmId);
        $model->setName($this->name);

        if ($model->save()) {
            // Добавление пустых ответов на вопросы для нового респондента
            $this->_creatorAnswers->create($model);
            // Удаление кэша формы создания
            $this->_cacheManager->deleteCache($this->cachePath);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось добавить нового респондента');
    }


    /**
     * @param $attr
     */
    public function uniqueName($attr)
    {
        $models = RespondsGcp::findAll(['confirm_id' => $this->confirmId]);

        foreach ($models as $item){

            if (mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }
}