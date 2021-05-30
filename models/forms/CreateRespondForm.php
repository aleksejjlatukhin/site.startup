<?php


namespace app\models\forms;

use app\models\CreatorAnswersForNewRespond;
use app\models\interfaces\ConfirmationInterface;
use app\models\Interview;
use app\models\Projects;
use app\models\Respond;
use app\models\Segment;
use app\models\User;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;

class CreateRespondForm extends FormCreateRespondent
{


    /**
     * CreateRespondForm constructor.
     * @param Interview $confirm
     * @param array $config
     */
    public function __construct(Interview $confirm, $config = [])
    {
        $this->_creatorAnswers = new CreatorAnswersForNewRespond();
        $this->_cacheManager = new CacheForm();
        $this->cachePath = self::getCachePath($confirm);
        $cacheName = 'formCreateRespondCache';
        if ($cache = $this->_cacheManager->getCache($this->cachePath, $cacheName)) {
            foreach ($cache[basename(self::class)] as $key => $value) $this[$key] = $value;
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
        $segment = Segment::findOne($confirm->segmentId);
        $project = Projects::findOne($segment->projectId);
        $user = User::findOne($project->userId);
        $cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/confirm/formCreateRespond/';
        return $cachePath;
    }


    /**
     * @return Respond
     * @throws ErrorException
     * @throws NotFoundHttpException
     */
    public function create ()
    {
        $model = new Respond();
        $model->setConfirmId($this->confirm_id);
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
        $models = Respond::findAll(['interview_id' => $this->confirmId]);

        foreach ($models as $item){

            if (mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }
}