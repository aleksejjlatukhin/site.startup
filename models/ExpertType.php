<?php


namespace app\models;


/**
 * Типы экспертов
 * Class ExpertType
 * @package app\models
 */
abstract class ExpertType
{

    const EXPERT_IN_SCIENTIFIC_FIELD = 1;
    const DEVELOPER_CONSTRUCTOR = 2;
    const DEVELOPER_PROGRAMMER = 3;
    const INDUSTRY_MARKETING_SPECIALIST = 4;
    const SPECIALIST_IN_INTELLECTUAL_PROPERTY_REGISTRATION = 5;
    const COMMUNICATIONS_SPECIALIST = 6;


    /**
     * @var array
     */
    private static $listTypes = [
        '1' => 'Ученый-эксперт в научной сфере',
        '2' => 'Разработчик-конструктор',
        '3' => 'Разработчик-программист',
        '4' => 'Отраслевой специалист по маркетингу',
        '5' => 'Специалист по регистрации ИС',
        '6' => 'Спецалист по коммуникациям'
    ];


    /**
     * @param User|null $expert
     * @param string|null $strKeys
     * @return array
     */
    public static function getListTypes($expert = null, $strKeys = null)
    {

        $list = array();

        if ($expert && !$strKeys) {

            $keys = self::getValue($expert->expertInfo->type);
            foreach ($keys as $key) {
                $list[$key] = self::$listTypes[$key];
            }

            return $list;

        } elseif (!$expert && $strKeys) {

            $keys = self::getValue($strKeys);
            foreach ($keys as $key) {
                $list[$key] = self::$listTypes[$key];
            }

            return $list;
        }

        return self::$listTypes;
    }


    /**
     * @param $value string
     * @return false|int|string
     */
    public static function getKey($value)
    {
        return array_search($value, self::$listTypes);
    }


    /**
     * @param string $types
     * @return array
     */
    public static function getValue($types)
    {
        return explode('|', $types);
    }


    /**
     * @param string $types
     * @return string
     */
    public static function getContent($types)
    {
        $array = array();
        foreach (self::getValue($types) as $value) {
            $array[] = self::getListTypes()[$value];
        }
        return implode(', ', $array);
    }
}