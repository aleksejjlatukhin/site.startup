<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;


class DescInterview extends \yii\db\ActiveRecord
{

    public $loadFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'desc_interview';
    }

    public function getRespond()
    {
        return $this->hasOne(Respond::class, ['id' => 'respond_id']);
    }

    public function upload($path)
    {
        if (!is_dir($path)){

            throw new NotFoundHttpException('Дирректория не существует!');

        }else{

            if ($this->validate()) {

                $filename=Yii::$app->getSecurity()->generateRandomString(15);
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['respond_id', 'description', 'result'], 'required'],
            [['respond_id', 'status'], 'integer'],
            [['description'], 'string'],
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
            'description' => 'Материалы, полученные в ходе интервью',
            'interview_file' => 'Файл',
            'result' => 'Варианты проблем',
            'status' => 'Данный респондент является представителем сегмента?',
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
            $this->respond->confirm->segment->project->touch('updated_at');
            $this->respond->confirm->segment->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->respond->confirm->segment->project->touch('updated_at');
            $this->respond->confirm->segment->project->user->touch('updated_at');
        });

        parent::init();
    }
}
