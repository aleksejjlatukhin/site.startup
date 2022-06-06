<?php


namespace app\models;

use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Класс для сортировки проектов по заданным параметрам
 *
 * Class ProjectSort
 * @package app\models
 */
class ProjectSort extends Model
{

    public static $array = [

        '0' => ['id' => '1', 'parent_id' => '0', 'name' => 'по наименованию'],
        '1' => ['id' => '2', 'parent_id' => '0', 'name' => 'по дате создания'],
        '2' => ['id' => '3', 'parent_id' => '0', 'name' => 'по дате изменения'],
        '3' => ['id' => '4', 'parent_id' => '1', 'name' => 'по алфавиту - от а до я', 'type_sort' => ['project_name' => SORT_ASC]],
        '4' => ['id' => '5', 'parent_id' => '1', 'name' => 'по алфавиту - от я до а', 'type_sort' => ['project_name' => SORT_DESC]],
        '5' => ['id' => '6', 'parent_id' => '2', 'name' => 'по первой дате', 'type_sort' => ['created_at' => SORT_ASC]],
        '6' => ['id' => '7', 'parent_id' => '2', 'name' => 'по последней дате', 'type_sort' => ['created_at' => SORT_DESC]],
        '7' => ['id' => '8', 'parent_id' => '3', 'name' => 'по первой дате', 'type_sort' => ['updated_at' => SORT_ASC]],
        '8' => ['id' => '9', 'parent_id' => '3', 'name' => 'по последней дате', 'type_sort' => ['updated_at' => SORT_DESC]],
    ];


    /**
     * @return array
     */
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


    /**
     * @param $area_id
     * @return array
     */
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


    /**
     * @param $user_id
     * @param $type_sort_id
     * @return array|ActiveRecord[]
     */
    public function fetchModels ($user_id, $type_sort_id)
    {
        $array_sort = self::$array;

        $key_arr = array_search($type_sort_id, array_column($array_sort, 'id'));

        $search_type_sort = $array_sort[$key_arr]['type_sort'];

        $models = Projects::find()->where(['user_id' => $user_id])->orderBy($search_type_sort)->all();

        return $models;
    }

}