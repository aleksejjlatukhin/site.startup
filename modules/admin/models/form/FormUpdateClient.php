<?php


namespace app\modules\admin\models\form;

use app\models\Client;
use yii\base\Model;
use yii\db\StaleObjectException;

/**
 * Форма редактирования информации о клиенте (организации)
 *
 * Class FormUpdateClient
 * @package app\modules\admin\models\form
 *
 * @property int $id                                    идентификатор клиента
 * @property string $name                               наименование клиента
 * @property string $fullname                           полное наименование клиента
 * @property string $city                               город клиента
 * @property string $description                        описание клиента (подробная информация о клиенте)
 * @property Client $_client                            объект организации
 */
class FormUpdateClient extends Model
{

    public $id;
    public $name;
    public $fullname;
    public $city;
    public $description;
    private $_client;


    /**
     * FormUpdateClient constructor.
     * @param Client $client
     * @param array $config
     */
    public function __construct(Client $client, $config = [])
    {
        $this->_client = $client;
        $this->attributes = $this->_client->attributes;
        parent::__construct($config);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'fullname', 'city', 'description'], 'required'],
            ['name', 'string', 'min' => 3, 'max' => 32],
            [['fullname', 'city'], 'string', 'max' => 255],
            ['description', 'string', 'max' => 2000],
            [['name', 'fullname', 'city', 'description'], 'trim'],
            ['_client', 'safe']
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование организации',
            'fullname' => 'Полное наименование организации',
            'city' => 'Город организации',
            'description' => 'Описание организации'
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function update()
    {
        $this->_client->attributes = $this->attributes;
        return $this->_client->update() ? true : false;
    }

}