<?php


namespace app\models\interfaces;

use yii\db\ActiveQuery;

/**
 * Интерфейс для классов, которые реализуют подтверждение гипотез
 *
 * Interface ConfirmationInterface
 * @package app\models\interfaces
 */
interface ConfirmationInterface
{

    /**
     * Получить вопросы привязанные к подтверждению
     * @return ActiveQuery
     */
    public function getQuestions();


    /**
     * Получить респондентов привязанных к подтверждению
     * @return ActiveQuery
     */
    public function getResponds();


    /**
     * Установить кол-во респондентов
     * @param int $count
     */
    public function setCountRespond($count);

    /**
     * @return int
     */
    public function getCountRespond();


    /**
     * @return int
     */
    public function getStage();


    /**
     * Получить гипотезу подтверждения
     * @return ActiveQuery
     */
    public function getHypothesis();
}