<?php

namespace app\models;

use Yii;
use yii\web\NotFoundHttpException;

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
            [['respond_id', 'date_fact', 'description', 'result'], 'required'],
            [['respond_id'], 'integer'],
            [['date_fact'], 'safe'],
            [['description'], 'string'],
            [['interview_file', 'server_file', 'result'], 'string', 'max' => 255],
            [['loadFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, odt, txt, doc, docx, pdf, xlsx',],
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
