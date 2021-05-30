<?php


namespace app\models\interfaces;


use yii\db\ActiveQuery;

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
     * @param $count
     */
    public function setCountRespond($count);


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