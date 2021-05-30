<?php

namespace app\models\forms;

use app\models\MessageAdmin;
use app\models\MessageFiles;
use Exception;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\UploadedFile;

class FormCreateMessageAdmin extends Model
{

    public $description;
    public $conversation_id;
    public $sender_id;
    public $adressee_id;

    public $message_files;
    public $category = MessageFiles::CATEGORY_ADMIN;
    public $message_id;
    public $server_file;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['description'], 'filter', 'filter' => 'trim'],
            [['description'], 'string', 'max' => 4000],
            [['server_file'], 'string', 'max' => 255],
            [['conversation_id','sender_id', 'adressee_id', 'message_id', 'category'], 'integer'],
            [['message_files'], 'file', 'extensions' => 'png, jpg, odt, xlsx, txt, doc, docx, pdf, otf, odp, pps, ppsx, ppt, pptx, opf, csv, xls', 'maxFiles' => 10],
        ];
    }


    /**
     * @return MessageAdmin|bool
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function create()
    {
        $model = new MessageAdmin();
        $model->description = $this->description;
        $model->conversation_id = $this->conversation_id;
        $model->sender_id = $this->sender_id;
        $model->adressee_id = $this->adressee_id;
        if ($model->save()) {

            //Загрузка презентационных файлов
            $this->message_id = $model->id;
            $this->message_files = UploadedFile::getInstances($this, 'message_files');
            if ($this->message_files) $this->uploadMessageFiles();

            return $model;
        }

        throw new NotFoundHttpException('Ошибка. Сообщение не отправлено');
    }


    /**
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    private function uploadMessageFiles(){

        $path = UPLOAD.'/user-'.$this->sender_id.'/messages/category-'.$this->category.'/message-'.$this->message_id.'/';
        if (!is_dir($path)) FileHelper::createDirectory($path);

        if($this->validate()){

            foreach($this->message_files as $file){

                $filename = Yii::$app->getSecurity()->generateRandomString(15);

                try{

                    $file->saveAs($path . $filename . '.' . $file->extension);

                    $messageFile = new MessageFiles();
                    $messageFile->file_name = $file;
                    $messageFile->server_file = $filename . '.' . $file->extension;
                    $messageFile->message_id = $this->message_id;
                    $messageFile->category = $this->category;
                    $messageFile->save(false);

                }catch (Exception $e){

                    throw new NotFoundHttpException('Невозможно загрузить файл!');
                }
            }
            return true;
        }else{
            return false;
        }

    }
}