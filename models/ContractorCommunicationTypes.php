<?php

namespace app\models;

/**
 * Типы коммуникаций между проектантом и исполнителем проекта
 *
 * Class ContractorCommunicationTypes
 * @package app\models
 */
class ContractorCommunicationTypes
{
    public const SIMPLE_USER_ASKS_ABOUT_READINESS_TO_JOIN_PROJECT = 1100;
    public const SIMPLE_USER_WITHDRAWS_REQUEST_ABOUT_READINESS_TO_JOIN_PROJECT = 1150;
    public const CONTRACTOR_ANSWERS_QUESTION_ABOUT_READINESS_TO_JOIN_PROJECT = 1200;
    public const SIMPLE_USER_APPOINTS_CONTRACTOR_PROJECT = 1300;
    public const SIMPLE_USER_DOES_NOT_APPOINTS_CONTRACTOR_PROJECT = 1350;
    public const SIMPLE_USER_WITHDRAWS_CONTRACTOR_FROM_PROJECT = 1400;

    public const USER_APPOINTS_SEGMENT_TASK_CONTRACTOR = 10011;
    public const USER_APPOINTS_CONFIRM_SEGMENT_TASK_CONTRACTOR = 10021;
    public const USER_APPOINTS_PROBLEM_TASK_CONTRACTOR = 10031;
    public const USER_APPOINTS_CONFIRM_PROBLEM_TASK_CONTRACTOR = 10041;
    public const USER_APPOINTS_GCP_TASK_CONTRACTOR = 10051;
    public const USER_APPOINTS_CONFIRM_GCP_TASK_CONTRACTOR = 10061;
    public const USER_APPOINTS_MVP_TASK_CONTRACTOR = 10071;
    public const USER_APPOINTS_CONFIRM_MVP_TASK_CONTRACTOR = 10081;

    //TODO: Проработать сценарий отправки коммуникаций
    // исполнителям при удалении этапов проекта проектантом
    public const USER_DELETED_PROJECT = 2000;
    public const USER_DELETED_SEGMENT = 2001;
    public const USER_DELETED_PROBLEM = 2003;
    public const USER_DELETED_GCP = 2005;
    public const USER_DELETED_MVP = 2007;

    /**
     * @return array
     */
    public static function getListTypes(): array
    {
        return [
            self::SIMPLE_USER_ASKS_ABOUT_READINESS_TO_JOIN_PROJECT,
            self::SIMPLE_USER_WITHDRAWS_REQUEST_ABOUT_READINESS_TO_JOIN_PROJECT,
            self::CONTRACTOR_ANSWERS_QUESTION_ABOUT_READINESS_TO_JOIN_PROJECT,
            self::SIMPLE_USER_APPOINTS_CONTRACTOR_PROJECT,
            self::SIMPLE_USER_DOES_NOT_APPOINTS_CONTRACTOR_PROJECT,
            self::SIMPLE_USER_WITHDRAWS_CONTRACTOR_FROM_PROJECT
        ];
    }
}