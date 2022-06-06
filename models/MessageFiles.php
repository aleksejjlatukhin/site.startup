<?php


namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс хранит информацию в бд о прикрепленных к сообщениям файлах
 *
 * Class MessageFiles
 * @package app\models
 *
 * @property int $id                                Идентификатор записи в таб. message_files
 * @property int $message_id                        Идентификатор сообщения
 * @property int $category                          Категория беседы, к которой относится сообщение
 * @property string $file_name                      Имя файла, которое передал пользователь
 * @property string $server_file                    Сгенерированное имя файла на сервере
 */
class MessageFiles extends ActiveRecord
{

    const CATEGORY_ADMIN = 1;
    const CATEGORY_MAIN_ADMIN = 2;
    const CATEGORY_TECHNICAL_SUPPORT = 3;
    const CATEGORY_EXPERT = 4;
    const CATEGORY_MANAGER = 5;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_files';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category', 'message_id'], 'integer'],
            [['file_name', 'server_file'], 'string', 'max' => 255],
            ['category', 'in', 'range' => [
                self::CATEGORY_ADMIN,
                self::CATEGORY_MAIN_ADMIN,
                self::CATEGORY_TECHNICAL_SUPPORT,
                self::CATEGORY_EXPERT,
                self::CATEGORY_MANAGER,
            ]],
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * @param string $file_name
     */
    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * @return string
     */
    public function getServerFile()
    {
        return $this->server_file;
    }

    /**
     * @param string $server_file
     */
    public function setServerFile($server_file)
    {
        $this->server_file = $server_file;
    }
}