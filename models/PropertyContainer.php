<?php


namespace app\models;

use yii\base\Exception;
use app\models\interfaces\PropertyContainerInterface;

/**
 * Класс для реализации паттерна проектирования "Контейнер свойств"
 *
 * Class PropertyContainer
 * @package app\models
 *
 * @property array $propertyContainer
 */
class PropertyContainer implements PropertyContainerInterface
{

    /**
     * @var array
     */
    private $propertyContainer = [];


    /**
     * @param $propertyName
     * @param $value
     * @return mixed
     */
    public function addProperty($propertyName, $value)
    {
        $this->propertyContainer[$propertyName] = $value;
    }


    /**
     * @param $propertyName
     * @return mixed
     */
    public function deleteProperty($propertyName)
    {
        unset($this->propertyContainer[$propertyName]);
    }


    /**
     * @param $propertyName
     * @return mixed
     */
    public function getProperty($propertyName)
    {
        return $this->propertyContainer[$propertyName] ? $this->propertyContainer[$propertyName] : null;
    }


    /**
     * @param $propertyName
     * @param $value
     * @return mixed
     * @throws Exception
     */
    public function setProperty($propertyName, $value)
    {
        if (!isset($this->propertyContainer[$propertyName])) {
            throw new Exception("Property $propertyName not found");
        }

        $this->propertyContainer[$propertyName] = $value;
    }
}