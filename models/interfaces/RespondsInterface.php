<?php

namespace app\models\interfaces;

use yii\db\ActiveQuery;

/**
 * Interface RespondsInterface
 * @package app\models\interfaces
 */
interface RespondsInterface
{
    /**
     * Получить модель подтверждения
     * @return mixed|ActiveQuery
     */
    public function getConfirm();

    /**
     * Получить интевью респондента
     * @return mixed|ActiveQuery
     */
    public function getInterview();

    /**
     * Получить ответы респондента на вопросы
     * @return mixed|ActiveQuery
     */
    public function getAnswers();

    /**
     * @return int
     */
    public function getId();

    /**
     * Установить id подтверждения
     * @param int $confirmId
     */
    public function setConfirmId($confirmId);

    /**
     * Получить id подтверждения
     * @return int
     */
    public function getConfirmId();

    /**
     * Установить имя респондента
     * @param string $name
     */
    public function setName($name);

    /**
     * Получить имя респондента
     * @return string
     */
    public function getName();

    /**
     * @param array $params
     */
    public function setParams(array $params);

    /**
     * @return string
     */
    public function getInfoRespond();

    /**
     * @param string $info_respond
     */
    public function setInfoRespond($info_respond);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     */
    public function setEmail($email);

    /**
     * @return int
     */
    public function getDatePlan();

    /**
     * @param int $datePlan
     */
    public function setDatePlan($datePlan);

    /**
     * @return string
     */
    public function getPlaceInterview();

    /**
     * @param string $place_interview
     */
    public function setPlaceInterview($place_interview);
}