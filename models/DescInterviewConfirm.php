<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


class DescInterviewConfirm extends \yii\db\ActiveRecord
{

    public $loadFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'desc_interview_confirm';
    }

    public function getRespond()
    {
        return $this->hasOne(RespondsConfirm::class, ['id' => 'responds_confirm_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['responds_confirm_id', 'status'], 'required'],
            [['responds_confirm_id', 'status'], 'integer'],
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
            'status' => 'Значимость проблемы'
        ];
    }

    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    public function init()
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->respond->confirm->problem->project->touch('updated_at');
            $this->respond->confirm->problem->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->respond->confirm->problem->project->touch('updated_at');
            $this->respond->confirm->problem->project->user->touch('updated_at');
        });

        parent::init();
    }


    /**
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    public function create()
    {
        $respond = RespondsConfirm::findOne($this->responds_confirm_id);
        $confirmProblem = ConfirmProblem::findOne($respond->confirm_problem_id);
        $problem = GenerationProblem::findOne($confirmProblem->gps_id);
        $segment = Segment::findOne($problem->segment_id);
        $project = Projects::findOne($problem->project_id);
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
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id. '/projects/project-'.$project->id.
                '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/confirm/interviews/respond-'.$respond->id;
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
        $respond = RespondsConfirm::findOne($this->responds_confirm_id);
        $confirmProblem = ConfirmProblem::findOne($respond->confirm_problem_id);
        $problem = GenerationProblem::findOne($confirmProblem->gps_id);
        $segment = Segment::findOne($problem->segment_id);
        $project = Projects::findOne($problem->project_id);
        $user = User::findOne($project->user_id);

        $path = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/interviews/respond-'.$respond->id.'/';
        if (!is_dir($path)) FileHelper::createDirectory($path);

        if ($this->validate()) {

            $filename = Yii::$app->getSecurity()->generateRandomString(15);
            try{

                $this->loadFile->saveAs($path . $filename . '.' . $this->loadFile->extension);
                $this->server_file = $filename . '.' . $this->loadFile->extension;

            }catch (\Exception $e){

                throw new NotFoundHttpException('Невозможно загрузить файл!');
            }

            return true;
        } else {
            return false;
        }
    }
}
