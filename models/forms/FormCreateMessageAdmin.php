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

/**
 * Форма создания сообщения между трекером и проектантом
 *
 * Class FormCreateMessageAdmin
 * @package app\models\forms
 *
 * @property string $description                        Текст сообщения
 * @property int $conversation_id                       Идентификатор беседы
 * @property int $sender_id                             Идентификатор отправителя
 * @property int $adressee_id                           Идентификатор получателя
 *
 * @property $message_files                             Прикрепленные файлы
 * @property int $category                              Категория к которой относится беседа
 * @property int $message_id                            Идентификатор созданного сообщения
 * @property string $server_file                        Сгенерированное имя прикрепленного файла для хранения на сервере
 */
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
        $model->setDescription($this->getDescription());
        $model->setConversationId($this->getConversationId());
        $model->setSenderId($this->getSenderId());
        $model->setAdresseeId($this->getAdresseeId());
        if ($model->save()) {

            //Загрузка презентационных файлов
            $this->setMessageId($model->getId());
            $this->setMessageFiles(UploadedFile::getInstances($this, 'message_files'));
            if ($this->getMessageFiles()) $this->uploadMessageFiles();

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

        $path = UPLOAD.'/user-'.$this->getSenderId().'/messages/category-'.$this->getCategory().'/message-'.$this->getMessageId().'/';
        if (!is_dir($path)) FileHelper::createDirectory($path);

        if($this->validate()){

            foreach($this->getMessageFiles() as $file){

                $filename = Yii::$app->getSecurity()->generateRandomString(15);

                try{

                    $file->saveAs($path . $filename . '.' . $file->extension);

                    $messageFile = new MessageFiles();
                    $messageFile->setFileName($file);
                    $messageFile->setServerFile($filename . '.' . $file->extension);
                    $messageFile->setMessageId($this->getMessageId());
                    $messageFile->setCategory($this->getCategory());
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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getConversationId()
    {
        return $this->conversation_id;
    }

    /**
     * @param int $conversation_id
     */
    public function setConversationId($conversation_id)
    {
        $this->conversation_id = $conversation_id;
    }

    /**
     * @return int
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }

    /**
     * @param int $sender_id
     */
    public function setSenderId($sender_id)
    {
        $this->sender_id = $sender_id;
    }

    /**
     * @return int
     */
    public function getAdresseeId()
    {
        return $this->adressee_id;
    }

    /**
     * @param int $adressee_id
     */
    public function setAdresseeId($adressee_id)
    {
        $this->adressee_id = $adressee_id;
    }

    /**
     * @return mixed
     */
    public function getMessageFiles()
    {
        return $this->message_files;
    }

    /**
     * @param mixed $message_files
     */
    public function setMessageFiles($message_files)
    {
        $this->message_files = $message_files;
    }

    /**
     * @return int
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param int $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return int
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * @param int $message_id
     */
    public function setMessageId($message_id)
    {
        $this->message_id = $message_id;
    }
}