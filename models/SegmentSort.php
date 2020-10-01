<?php


namespace app\models;

use yii\base\Model;

class SegmentSort extends Model
{
    public static $array = [

        '0' => ['id' => '1', 'parent_id' => '0', 'name' => 'по последовательности'],
        '1' => ['id' => '2', 'parent_id' => '0', 'name' => 'по наличию подтверждения'],
        '2' => ['id' => '3', 'parent_id' => '0', 'name' => 'по наименованию'],
        '3' => ['id' => '4', 'parent_id' => '0', 'name' => 'по типу'],
        '4' => ['id' => '5', 'parent_id' => '0', 'name' => 'по сфере деятельности'],
        '5' => ['id' => '6', 'parent_id' => '0', 'name' => 'по виду деятельности'],
        '6' => ['id' => '7', 'parent_id' => '0', 'name' => 'по специализации'],
        '7' => ['id' => '8', 'parent_id' => '0', 'name' => 'по объему рынка'],
        '8' => ['id' => '9', 'parent_id' => '1', 'name' => 'сначала старые'],
        '9' => ['id' => '10', 'parent_id' => '1', 'name' => 'сначала новые'],
        '10' => ['id' => '11', 'parent_id' => '2', 'name' => 'сначала ожидающие подтверждения'],
        '11' => ['id' => '12', 'parent_id' => '2', 'name' => 'сначала подтвержденные'],
        '12' => ['id' => '13', 'parent_id' => '2', 'name' => 'сначала неподтвержденные'],
        '13' => ['id' => '14', 'parent_id' => '3', 'name' => 'по алфавиту - от а до я'],
        '14' => ['id' => '15', 'parent_id' => '3', 'name' => 'по алфавиту - от я до а'],
        '15' => ['id' => '16', 'parent_id' => '4', 'name' => 'сначала по типу B2C'],
        '16' => ['id' => '17', 'parent_id' => '4', 'name' => 'сначала по типу B2B'],
        '17' => ['id' => '18', 'parent_id' => '5', 'name' => 'по алфавиту - от а до я'],
        '18' => ['id' => '19', 'parent_id' => '5', 'name' => 'по алфавиту - от я до а'],
        '19' => ['id' => '20', 'parent_id' => '6', 'name' => 'по алфавиту - от а до я'],
        '20' => ['id' => '21', 'parent_id' => '6', 'name' => 'по алфавиту - от я до а'],
        '21' => ['id' => '22', 'parent_id' => '7', 'name' => 'по алфавиту - от а до я'],
        '22' => ['id' => '23', 'parent_id' => '7', 'name' => 'по алфавиту - от я до а'],
        '23' => ['id' => '24', 'parent_id' => '8', 'name' => 'по возрастанию'],
        '24' => ['id' => '25', 'parent_id' => '8', 'name' => 'по убыванию'],

    ];


    public static function getListFields()
    {
        $listFields = self::$array;

        foreach ($listFields as $key => $field) {

            if ($listFields[$key]['parent_id'] != 0) {

                unset($listFields[$key]);
            }
        }

        return $listFields;
    }

    public static function getListTypes($area_id)
    {
        $listTypes = self::$array;

        foreach ($listTypes as $key => $type) {

            if ($listTypes[$key]['parent_id'] != $area_id) {

                unset($listTypes[$key]);
            }
        }

        return $listTypes;
    }


}