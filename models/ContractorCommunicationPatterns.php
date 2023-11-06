<?php

namespace app\models;

class ContractorCommunicationPatterns
{
    public const DEFAULT_CONTRACTOR_ACCESS_TO_PROJECT = 14;
    public const COMMUNICATION_DEFAULT_ABOUT_READINESS_TO_JOIN_PROJECT = 'Вы готовы принять участие в работе по проекту {{наименование проекта, ссылка на проект}} ? Для предварительной оценки Вам открыт доступ к проекту на 14 дней. Вид деятельности: {{вид деятельности исполнителя}}.';
    public const COMMUNICATION_DEFAULT_CONTRACTOR_ANSWERS_QUESTION_ABOUT_READINESS_TO_JOIN_PROJECT = 'Ответ исполнителя на запрос о готовности принять участие в работе по проекту {{наименование проекта, ссылка на проект}} ? Вид деятельности: {{вид деятельности исполнителя}}.';
    public const COMMUNICATION_DEFAULT_WITHDRAWS_REQUEST_ABOUT_READINESS_TO_JOIN_PROJECT = 'Произошли изменения в проекте {{наименование проекта}}. Приносим Вам свои извинения, запрос на участие в работе отозван. Вид деятельности: {{вид деятельности исполнителя}}.';
    public const COMMUNICATION_DEFAULT_APPOINTS_CONTRACTOR_PROJECT = 'Вы назначены на проект {{наименование проекта, ссылка на проект}} по виду деятельности: {{вид деятельности исполнителя}}. Ожидайте, когда руководитель проекта создаст для Вас задание, Вы получите новое уведомление.';
    public const COMMUNICATION_DEFAULT_DOES_NOT_APPOINTS_CONTRACTOR_PROJECT = 'Вы не назначены на проект {{наименование проекта}}. Приносим Вам свои извинения, запрос на участие в работе отозван. Вид деятельности: {{вид деятельности исполнителя}}.';
    public const COMMUNICATION_DEFAULT_WITHDRAWS_CONTRACTOR_FROM_PROJECT = 'Вы отозваны с проекта {{наименование проекта}}. Подробную информацию получите у руководителя проекта. Вид деятельности: {{вид деятельности исполнителя}}.';
    public const COMMUNICATION_DEFAULT_USER_CREATED_TASK_STAGE_PROJECT = 'Руководить проекта, {{проектант}}, создал для Вас задание по этапу «{{наименование этапа проекта, ссылка на этап проекта}}».<br>Проект: {{наименование проекта}}.<br>Вид деятельности: {{вид деятельности исполнителя}}.';
    public const COMMUNICATION_DEFAULT_USER_DELETED_STAGE_PROJECT = 'Руководить проекта, {{проектант}}, удалил «{{наименование этапа проекта, ссылка на этап проекта}}».<br>Проект: {{наименование проекта}}.';
}