<?php


namespace app\models;

/**
 * Типы коммуникаций между администратором и экспертом
 *
 * Class CommunicationTypes
 * @package app\models
 */
class CommunicationTypes
{

    public const MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE = 100;
    public const MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE = 150;
    public const EXPERT_ANSWERS_QUESTION_ABOUT_READINESS_CONDUCT_EXPERTISE = 200;
    public const MAIN_ADMIN_APPOINTS_EXPERT_PROJECT = 300;
    public const MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT = 350;
    public const MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT = 400;


    /**
     * @return array
     */
    public static function getListTypes(): array
    {
        return [
            self::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE,
            self::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE,
            self::EXPERT_ANSWERS_QUESTION_ABOUT_READINESS_CONDUCT_EXPERTISE,
            self::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT,
            self::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT,
            self::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT,

        ];
    }
}