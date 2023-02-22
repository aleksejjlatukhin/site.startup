<?php

namespace app\models\forms;

use yii\base\Model;

/**
 * Форма для фильтрации запросов B2B компаний
 *
 * Class FormSegment
 * @package app\models\forms
 *
 * @property string $requirement                         Запрос B2B компаний
 * @property string $reason                              Причина запроса B2B компаний
 * @property string $expectedResult                      Ожидаемый результат
 * @property string $fieldOfActivity                     Сфера деятельности предприятия
 * @property string $sortOfActivity                      Вид деятельности предприятия
 * @property string $size                              Размер предприятия по количеству персонала
 * @property string $locationId                        Идентификатор локации предприятия
 * @property string $typeCompany                       Тип предприятия
 * @property string $typeProduction                    Тип производства
 */
class FormFilterRequirement extends Model
{
    public $requirement;
    public $reason;
    public $expectedResult;
    public $fieldOfActivity;
    public $sortOfActivity;
    public $size;
    public $locationId;
    public $typeCompany;
    public $typeProduction;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['requirement', 'reason', 'expectedResult', 'fieldOfActivity', 'sortOfActivity'], 'string', 'max' => 255],
            [['requirement', 'reason', 'expectedResult', 'fieldOfActivity', 'sortOfActivity'], 'trim'],
            [['size', 'locationId', 'typeCompany', 'typeProduction'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'requirement' => 'Запрос',
            'reason' => 'Причина запроса',
            'expectedResult' => 'Ожидаемый результат',
            'fieldOfActivity' => 'Сфера деятельности предприятия',
            'sortOfActivity' => 'Вид деятельности предприятия',
            'size' => 'Размер предприятия по количеству персонала',
            'locationId' => 'Локация предприятия',
            'typeCompany' => 'Тип предприятия',
            'typeProduction' => 'Тип производства',
        ];
    }

    /**
     * @return string
     */
    public function getRequirement(): string
    {
        return $this->requirement;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @return string
     */
    public function getExpectedResult(): string
    {
        return $this->expectedResult;
    }

    /**
     * @return string
     */
    public function getFieldOfActivity(): string
    {
        return $this->fieldOfActivity;
    }

    /**
     * @return string
     */
    public function getSortOfActivity(): string
    {
        return $this->sortOfActivity;
    }

    /**
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getLocationId(): string
    {
        return $this->locationId;
    }

    /**
     * @return string
     */
    public function getTypeCompany(): string
    {
        return $this->typeCompany;
    }

    /**
     * @return string
     */
    public function getTypeProduction(): string
    {
        return $this->typeProduction;
    }
}