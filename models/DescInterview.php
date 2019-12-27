<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "desc_interview".
 *
 * @property string $id
 * @property int $respond_id
 * @property string $date_fact
 * @property string $description
 */
class DescInterview extends \yii\db\ActiveRecord
{

    public $loadFile;

    public $exist_desc;

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
            [['respond_id', 'date_fact', 'description', 'result'], 'required'],
            [['respond_id'], 'integer'],
            [['date_fact'], 'safe'],
            [['description'], 'string'],
            [['interview_file', 'result'], 'string', 'max' => 255],
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
            'respond_id' => 'Respond ID',
            'date_fact' => 'Фактическая дата интервью',
            'description' => 'Материалы интервью',
            'interview_file' => 'Файл',
            'result' => 'Вывод',
            'status' => 'Данный респондент является представителем сегмента?',
        ];
    }
}
