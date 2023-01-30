<?php

namespace app\modules\admin\models\form;

use app\models\User;
use app\models\WishList;
use Yii;
use yii\base\Model;

/**
 * Форма создания списка запросов B2B
 *
 * Class FormCreateRatesPlan
 * @package app\modules\admin\models\form
 *
 * @property integer $size                                          размер предприятия по количеству персонала
 * @property integer $location_id                                   идентификатор локации(города) предприятия
 * @property integer $type_company                                  тип предприятия
 * @property integer $type_production                               тип производства
 * @property string $add_info                                       дополнительная информация
 *
 * @property WishList $_model
 */
class FormCreateWishList extends Model
{
    public $size;
    public $location_id;
    public $type_company;
    public $type_production;
    public $add_info;
    public $_model;

    public function __construct($config = [])
    {
        $this->_model = new WishList();

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['size', 'location_id', 'type_company', 'type_production', 'add_info'], 'required'],
            [['add_info'], 'string', 'max' => 2000],
            [['size', 'location_id', 'type_company', 'type_production',], 'integer'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'size' => 'Размер предприятия по количеству персонала',
            'location_id' => 'Локация предприятия (город)',
            'type_company' => 'Тип предприятия',
            'type_production' => 'Тип производства',
            'add_info' => 'Дополнительная информация'
        ];
    }

    /**
     * @return bool
     */
    public function create(): bool
    {
        $user = User::findOne(Yii::$app->user->getId());
        $this->_model->setClientId($user->clientUser->getClientId());
        $this->_model->setSize($this->getSize());
        $this->_model->setLocationId($this->getLocationId());
        $this->_model->setTypeCompany($this->getTypeCompany());
        $this->_model->setTypeProduction($this->getTypeProduction());
        $this->_model->setAddInfo($this->getAddInfo());
        return $this->_model->save();
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @return int
     */
    public function getLocationId(): int
    {
        return $this->location_id;
    }

    /**
     * @param int $location_id
     */
    public function setLocationId(int $location_id): void
    {
        $this->location_id = $location_id;
    }

    /**
     * @return int
     */
    public function getTypeCompany(): int
    {
        return $this->type_company;
    }

    /**
     * @param int $type_company
     */
    public function setTypeCompany(int $type_company): void
    {
        $this->type_company = $type_company;
    }

    /**
     * @return int
     */
    public function getTypeProduction(): int
    {
        return $this->type_production;
    }

    /**
     * @param int $type_production
     */
    public function setTypeProduction(int $type_production): void
    {
        $this->type_production = $type_production;
    }

    /**
     * @return string
     */
    public function getAddInfo(): string
    {
        return $this->add_info;
    }

    /**
     * @param string $add_info
     */
    public function setAddInfo(string $add_info): void
    {
        $this->add_info = $add_info;
    }
}