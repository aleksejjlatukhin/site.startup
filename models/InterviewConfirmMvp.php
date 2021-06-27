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


class InterviewConfirmMvp extends ActiveRecord
{

    public $loadFile;
    public $_cacheManager;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'interview_confirm_mvp';
    }


    /**
     * InterviewConfirmMvp constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->_cacheManager = new CacheForm();

        parent::__construct($config);
    }


    /**
     * Получить объект респондента
     * @return ActiveQuery
     */
    public function getRespond()
    {
        return $this->hasOne(RespondsMvp::class, ['id' => 'respond_id']);
    }


    /**
     * @param $id
     */
    public function setRespondId($id)
    {
        $this->respond_id = $id;
    }


    /**
     * @return mixed
     */
    public function getRespondId()
    {
        return $this->respond_id;
    }


    /**
     * @return string
     */
    public function getPathFile()
    {
        $respond = $this->respond;
        $confirm = $respond->confirm;
        $mvp = $confirm->mvp;
        $gcp = $mvp->gcp;
        $problem = $mvp->problem;
        $segment = $mvp->segment;
        $project = $mvp->project;
        $user = $project->user;
        $path = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/segments/segment-'.$segment->id.
            '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/mvps/mvp-'.$mvp->id.'/interviews/respond-'.$respond->id.'/';

        return $path;
    }


    /**
     * @param RespondsMvp $respond
     * @return string
     */
    public static function getCachePath($respond)
    {
        $confirm = $respond->confirm;
        $mvp = $confirm->mvp;
        $gcp = $mvp->gcp;
        $problem = $mvp->problem;
        $segment = $mvp->segment;
        $project = $mvp->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->id. '/projects/project-'.$project->id. '/segments/segment-'.$segment->id.
            '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/mvps/mvp-'.$mvp->id.'/confirm/interviews/respond-'.$respond->id.'/';

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
            'status' => 'Значимость MVP',
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
            $this->respond->confirm->mvp->project->touch('updated_at');
            $this->respond->confirm->mvp->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->respond->confirm->mvp->project->touch('updated_at');
            $this->respond->confirm->mvp->project->user->touch('updated_at');
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

            $this->loadFile = UploadedFile::getInstance($this, 'loadFile');

            if ($this->loadFile) {
                if ($this->uploadFileInterview()) {
                    $this->interview_file = $this->loadFile;
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

            $this->loadFile = UploadedFile::getInstance($this, 'loadFile');

            if ($this->loadFile) {
                if ($this->uploadFileInterview()) {
                    $this->interview_file = $this->loadFile;
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
        $path = $this->pathFile;
        if (!is_dir($path)) FileHelper::createDirectory($path);

        if ($this->validate()) {

            $filename = Yii::$app->getSecurity()->generateRandomString(15);
            try{

                $this->loadFile->saveAs($path . $filename . '.' . $this->loadFile->extension);
                $this->server_file = $filename . '.' . $this->loadFile->extension;

            }catch (Exception $e){

                throw new NotFoundHttpException('Невозможно загрузить файл!');
            }

            return true;
        } else {
            return false;
        }
    }
}
