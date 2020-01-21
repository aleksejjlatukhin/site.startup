<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback_expert_gcp".
 *
 * @property string $id
 * @property int $confirm_gcp_id
 * @property string $title
 * @property string $name
 * @property string $position
 * @property string $feedback_file
 * @property string $comment
 * @property string $date_feedback
 */
class FeedbackExpertGcp extends \yii\db\ActiveRecord
{

    public $loadFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback_expert_gcp';
    }

    public function getGcp()
    {
        return $this->hasOne(ConfirmGcp::class, ['id' => 'confirm_gcp_id']);
    }

    public function upload($path)
    {
        if ($this->validate()) {
            $this->loadFile->saveAs($path . $this->loadFile->baseName . '.' . $this->loadFile->extension);
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
            [['confirm_gcp_id', 'title', 'name', 'comment'], 'required'],
            [['confirm_gcp_id'], 'integer'],
            [['date_feedback'], 'safe'],
            [['title', 'name', 'position', 'feedback_file', 'comment'], 'string', 'max' => 255],
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
            'confirm_gcp_id' => 'Confirm Gcp ID',
            'title' => 'Название отзыва',
            'name' => 'ФИО эксперта',
            'position' => 'Организация / Должность',
            'feedback_file' => 'Отзыв(файл)',
            'comment' => 'Комментарий',
            'date_feedback' => 'Дата',
        ];
    }
}
