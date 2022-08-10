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
     * Дублирование коммуникации от гл.админа эксперту (назначение или отзыв с проекта)
     * Отправка происходит трекеру и проектанту
     */
    public const MAIN_ADMIN_TO_EXPERT = 333;

    /**
     * Отправка коммуникации трекеру и проектанту
     * при завершении экспертом этапа экспертизы по проекту
     */
    public const EXPERT_COMPLETED_EXPERTISE = 432;

    /**
     * Отправка коммуникации трекеру и проектанту
     * при обновлении экспертом данных завершенной экспертизы по этапу
     */
    public const EXPERT_UPDATE_DATA_COMPLETED_EXPERTISE = 433;


    /**
     * Все типы дублирующих коммуникаций
     *
     * @return array
     */
    public static function getTypes(): array
    {
        return [
            self::MAIN_ADMIN_TO_EXPERT,
            self::EXPERT_COMPLETED_EXPERTISE,
            self::EXPERT_UPDATE_DATA_COMPLETED_EXPERTISE
        ];
    }
}