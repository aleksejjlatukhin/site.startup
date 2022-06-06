<?php

namespace app\models;

use app\models\forms\CacheForm;
use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Класс хранит информацию в бд о проведении интервью с респондентом
 * на этапе подтверждения гипотезы ценностного предложения
 *
 * Class InterviewConfirmGcp
 * @package app\models
 *
 * @property int $id                                    Идентификатор записи
 * @property int $respond_id                            Идентификатор респондента из таб. responds_gcp
 * @property string $interview_file                     Имя файла, с которым он был загружен
 * @property string $server_file                        Сгенерированное имя прикрепленного файла на сервере
 * @property int $status                                Значимость ЦП для респондента
 * @property int $created_at                            Дата создания
 * @property int $updated_at                            Дата редактирования
 * @property $loadFile                                  Поле для загрузки файла
 * @property CacheForm $_cacheManager                   Менеджер кэширования
 */
class InterviewConfirmGcp extends ActiveRecord
{

    public $loadFile;
    public $_cacheManager;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'interview_confirm_gcp';
    }

    /**
     * InterviewConfirmGcp constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->setCacheManager();
        parent::__construct($config);
    }

    /**
     * Получить объект респондента
     *
     * @return ActiveQuery
     */
    public function getRespond()
    {
        return $this->hasOne(RespondsGcp::class, ['id' => 'respond_id']);
    }

    /**
     * @return RespondsGcp|null
     */
    public function findRespond()
    {
        return RespondsGcp::findOne($this->getRespondId());
    }

    /**
     * @return string
     */
    public function getPathFile()
    {
        $respond = $this->findRespond();
        $confirm = $respond->findConfirm();
        $gcp = $confirm->findGcp();
        $problem = $gcp->findProblem();
        $segment = $gcp->findSegment();
        $project = $gcp->findProject();
        $user = $project->findUser();
        $path = UPLOAD.'/user-'.$user->getId().'/project-'.$project->getId().'/segments/segment-'.$segment->getId().
            '/problems/problem-'.$problem->getId().'/gcps/gcp-'.$gcp->getId().'/interviews/respond-'.$respond->getId().'/';

        return $path;
    }

    /**
     * @param RespondsGcp $respond
     * @return string
     */
    public static function getCachePath($respond)
    {
        $confirm = $respond->findConfirm();
        $gcp = $confirm->findGcp();
        $problem = $gcp->findProblem();
        $segment = $gcp->findSegment();
        $project = $gcp->findProject();
        $user = $project->findUser();
        $cachePath = '../runtime/cache/forms/user-'.$user->getId(). '/projects/project-'.$project->getId(). '/segments/segment-'.$segment->getId().
            '/problems/problem-'.$problem->getId().'/gcps/gcp-'.$gcp->getId().'/confirm/interviews/respond-'.$respond->getId().'/';

        return $cachePath;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['respond_id', 'status'], 'required'],
            [['respond_id', 'status'], 'integer'],
            [['interview_file', 'server_file'], 'string', 'max' => 255],
            [['loadFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, odt, txt, doc, docx, pdf, xlsx, otf, odp, pps, ppsx, ppt, pptx, opf, csv, xls',],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'interview_file' => 'Файл',
            'status' => 'Значимость предложения'
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    public function init()
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->respond->confirm->gcp->project->touch('updated_at');
            $this->respond->confirm->gcp->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->respond->confirm->gcp->project->touch('updated_at');
            $this->respond->confirm->gcp->project->user->touch('updated_at');
        });

        parent::init();
    }

    /**
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function create()
    {
        if ($this->validate() && $this->save()) {

            $this->setLoadFile(UploadedFile::getInstance($this, 'loadFile'));

            if ($this->getLoadFile()) {
                if ($this->uploadFileInterview()) {
                    $this->setInterviewFile($this->getLoadFile());
                    $this->save(false);
                }
            }

            return true;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить интервью');
    }

    /**
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function updateInterview()
    {
        if ($this->validate() && $this->save()) {

            $this->setLoadFile(UploadedFile::getInstance($this, 'loadFile'));

            if ($this->getLoadFile()) {
                if ($this->uploadFileInterview()) {
                    $this->setInterviewFile($this->getLoadFile());
                    $this->save(false);
                }
            }

            return true;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось обновить данные интервью');
    }

    /**
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    private function uploadFileInterview()
    {
        $path = $this->getPathFile();
        if (!is_dir($path)) FileHelper::createDirectory($path);

        if ($this->validate()) {

            $filename = Yii::$app->getSecurity()->generateRandomString(15);
            try{

                $this->getLoadFile()->saveAs($path . $filename . '.' . $this->getLoadFile()->extension);
                $this->setServerFile($filename . '.' . $this->getLoadFile()->extension);

            }catch (Exception $e){

                throw new NotFoundHttpException('Невозможно загрузить файл!');
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setRespondId($id)
    {
        $this->respond_id = $id;
    }


    /**
     * @return int
     */
    public function getRespondId()
    {
        return $this->respond_id;
    }

    /**
     * @return string
     */
    public function getInterviewFile()
    {
        return $this->interview_file;
    }

    /**
     * @param string $interview_file
     */
    public function setInterviewFile($interview_file)
    {
        $this->interview_file = $interview_file;
    }

    /**
     * @return string
     */
    public function getServerFile()
    {
        return $this->server_file;
    }

    /**
     * @param string $server_file
     */
    public function setServerFile($server_file)
    {
        $this->server_file = $server_file;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @return mixed
     */
    public function getLoadFile()
    {
        return $this->loadFile;
    }

    /**
     * @param mixed $loadFile
     */
    public function setLoadFile($loadFile)
    {
        $this->loadFile = $loadFile;
    }

    /**
     * @return CacheForm
     */
    public function getCacheManager()
    {
        return $this->_cacheManager;
    }

    /**
     *
     */
    public function setCacheManager()
    {
        $this->_cacheManager = new CacheForm();
    }
}
