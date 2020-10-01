<?php


namespace app\models;

use yii\base\Model;

class ProjectSort extends Model
{
    public static $array = [

        '0' => ['id' => '1', 'parent_id' => '0', 'name' => 'по наименованию'],
        '1' => ['id' => '2', 'parent_id' => '0', 'name' => 'по дате создания'],
        '2' => ['id' => '3', 'parent_id' => '0', 'name' => 'по дате изменения'],
        '3' => ['id' => '4', 'parent_id' => '1', 'name' => 'по алфавиту - от а до я'],
        '4' => ['id' => '5', 'parent_id' => '1', 'name' => 'по алфавиту - от я до а'],
        '5' => ['id' => '6', 'parent_id' => '2', 'name' => 'по первой дате'],
        '6' => ['id' => '7', 'parent_id' => '2', 'name' => 'по последней дате'],
        '7' => ['id' => '8', 'parent_id' => '3', 'name' => 'по первой дате'],
        '8' => ['id' => '9', 'parent_id' => '3', 'name' => 'по последней дате'],
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