<?php

namespace app\models;

use Yii;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "feedback_expert_confirm".
 *
 * @property string $id
 * @property int $confirm_problem_id
 * @property string $title
 * @property string $name
 * @property string $position
 * @property string $feedback_file
 * @property string $comment
 * @property string $date_feedback
 */
class FeedbackExpertConfirm extends \yii\db\ActiveRecord
{

    public $loadFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback_expert_confirm';
    }

    /*Подтверждение ГПС*/
    public function getProblem()
    {
        return $this->hasOne(ConfirmProblem::class, ['id' => 'confirm_problem_id']);
    }


    /*Гипотеза проблемы сегмента*/
    public function getGenProblem()
    {
        return $this->problem->problem;
    }

    /*Гипотеза целевого сегмента*/
    public function getSegment()
    {
        return $this->genProblem->interview->segment;
    }

    /*Проект*/
    public function getProject()
    {
        return $this->segment->project;
    }



    public function getUser()
    {
        return $this->project->user;
    }



    public function upload($path)
    {
        if (!is_dir($path)){

            throw new NotFoundHttpException('Дирректория не существует!');

        }else{

            if ($this->validate()) {

                //$filename = $this->loadFile->baseName;
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
            [['confirm_problem_id', 'title', 'name', 'comment'], 'required'],
            [['confirm_problem_id'], 'integer'],
            [['date_feedback'], 'safe'],
            [['title', 'name', 'position', 'feedback_file', 'server_file', 'comment'], 'string', 'max' => 255],
            [['loadFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, odt, xlsx, txt, doc, docx, pdf, otf, odp, pps, ppsx, ppt, pptx, opf, csv, xls',],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'confirm_problem_id' => 'Confirm Problem ID',
            'title' => 'Название отзыва',
            'name' => 'ФИО эксперта',
            'position' => 'Организация / Должность',
            'feedback_file' => 'Отзыв(файл)',
            'comment' => 'Комментарий',
            'date_feedback' => 'Дата',
        ];
    }
}
