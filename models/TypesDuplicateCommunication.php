<?php


namespace app\models;


/**
 * Типы дублирующих коммуникаций
 *
 * Class TypesDuplicateCommunication
 * @package app\models
 */
class TypesDuplicateCommunication
{

    /**
     * Коммуникации по проекту между
     * экспертом и гд.админом
     */
    const PROJECT_COMMUNICATIONS = 333;


    /**
     * Все типы дублирующих коммуникаций
     *
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::PROJECT_COMMUNICATIONS
        ];
    }
}