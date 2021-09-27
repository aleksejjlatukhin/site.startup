<?php


namespace app\models\interfaces;


interface CommunicationsInterface
{

    /**
     * Получить id коммуникации
     * @return int
     */
    public function getId();

    /**
     * Получить id отправителя коммуникации
     * @return int
     */
    public function getSenderId();


    /**
     * Получить id получателя коммуникации
     * @return int
     */
    public function getAdresseeId();


    /**
     * Установить параметр
     * прочтения коммуникации
     */
    public function setStatusRead();

}