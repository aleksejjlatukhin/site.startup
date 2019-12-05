<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback_expert".
 *
 * @property string $id
 * @property int $interview_id
 * @property string $name
 * @property string $position
 * @property string $feedback_file
 * @property string $comment
 */
class FeedbackExpert extends \yii\db\ActiveRecord
{

    public $loadFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback_expert';
    }

    public function getInterview()
    {
        return $this->hasOne(Interview::class, ['id' => 'interview_id']);
    }


    public function upload()
    {
        if ($this->validate()) {
            $this->loadFile->saveAs('upload/feedbacks/' . $this->loadFile->baseName . '.' . $this->loadFile->extension);
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['interview_id', 'name', 'comment', 'title'], 'required'],
            [['interview_id'], 'integer'],
            [['name', 'position', 'feedback_file', 'comment', 'title'], 'string', 'max' => 255],
            [['date_feedback'], 'safe'],
            [['loadFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, odt, xlsx, txt, doc, docx, pdf',],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'interview_id' => 'Interview ID',
            'title' => 'Название отзыва',
            'name' => 'ФИО эксперта',
            'position' => 'Организация / Должность',
            'feedback_file' => 'Отзыв',
            'comment' => 'Комментарий',
            'date_feedback' => 'Дата',
        ];
    }
}
