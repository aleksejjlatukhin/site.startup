<?php

namespace app\models;

use Exception;
use Yii;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


class DescInterview extends ActiveRecord
{

    public $loadFile;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'desc_interview';
    }


    /**
     * Получить объект респондента
     * @return ActiveQuery
     */
    public function getRespond()
    {
        return $this->hasOne(Respond::class, ['id' => 'respond_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['respond_id', 'result'], 'required'],
            [['respond_id', 'status'], 'integer'],
            [['interview_file', 'server_file'], 'string', 'max' => 255],
            [['result'], 'string', 'max' => 2000],
            [['loadFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, odt, txt, doc, docx, pdf, xlsx, otf, odp, pps, ppsx, ppt, pptx, opf, csv, xls',],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'respond_id' => 'Respond ID',
            'interview_file' => 'Файл',
            'result' => 'Варианты проблем',
            'status' => 'Данный респондент является представителем сегмента?',
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
            $this->respond->confirm->segment->project->touch('updated_at');
            $this->respond->confirm->segment->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->respond->confirm->segment->project->touch('updated_at');
            $this->respond->confirm->segment->project->user->touch('updated_at');
        });

        parent::init();
    }


    /**
     * @throws NotFoundHttpException
     * @throws ErrorException
     * @throws \yii\base\Exception
     */
    public function create()
    {
        $respond = Respond::findOne($this->respond_id);
        $confirmSegment = Interview::findOne($respond->interview_id);
        $segment = Segment::findOne($confirmSegment->segment_id);
        $project = Projects::findOne($segment->project_id);
        $user = User::findOne($project->user_id);

        if ($this->validate() && $this->save()) {

            $this->loadFile = UploadedFile::getInstance($this, 'loadFile');

            if ($this->loadFile) {
                if ($this->uploadFileInterview()) {
                    $this->interview_file = $this->loadFile;
                    $this->save(false);
                }
            }

            // Удаление кэша для форм респондента
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/confirm/interviews/respond-'.$respond->id;
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

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
        $respond = Respond::findOne($this->respond_id);
        $confirmSegment = Interview::findOne($respond->interview_id);
        $segment = Segment::findOne($confirmSegment->segment_id);
        $project = Projects::findOne($segment->project_id);
        $user = User::findOne($project->user_id);

        $path = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/segments/segment-'.$segment->id.'/interviews/respond-'.$respond->id.'/';
        if (!is_dir($path)) FileHelper::createDirectory($path);

        if ($this->validate()) {

            $filename=Yii::$app->getSecurity()->generateRandomString(15);
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
