<?php

namespace app\models\interfaces;

use yii\db\ActiveQuery;

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
     * Установить id подтверждения
     * @param $confirmId
     * @return mixed
     */
    public function setConfirmId($confirmId);


    /**
     * Получить id подтверждения
     * @return mixed
     */
    public function getConfirmId();


    /**
     * Установить имя респондента
     * @param $name
     * @return mixed
     */
    public function setName($name);


    /**
     * Получить имя респондента
     * @return mixed
     */
    public function getName();


    /**
     * @param array $params
     * @return mixed
     */
    public function setParams(array $params);
}