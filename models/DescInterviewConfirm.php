<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "desc_interview_confirm".
 *
 * @property string $id
 * @property int $responds_confirm_id
 * @property string $date_fact
 * @property string $description
 * @property string $interview_file
 */
class DescInterviewConfirm extends \yii\db\ActiveRecord
{

    public $loadFile;

    public $exist_desc;

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
            [['responds_confirm_id', 'date_fact', 'description'], 'required'],
            [['responds_confirm_id'], 'integer'],
            [['date_fact'], 'safe'],
            [['description'], 'string'],
            [['interview_file'], 'string', 'max' => 255],
            [['loadFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, odt, xlsx, txt, doc, docx, pdf',],
            ['exist_desc', 'boolean'],
            ['status', 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'responds_confirm_id' => 'Responds Confirm ID',
            'date_fact' => 'Фактическая дата интервью',
            'description' => 'Материалы интервью',
            'interview_file' => 'Файл',
            'status' => 'Индикатор теста'
        ];
    }
}
