<?php


namespace app\models;

use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;

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


    private function fetchModels ($user_id, $type_sort_id)
    {
        $array_sort = self::$array;

        $key_arr = array_search($type_sort_id, array_column($array_sort, 'id'));

        $search_type_sort = $array_sort[$key_arr]['type_sort'];

        $models = Projects::find()->where(['user_id' => $user_id])->orderBy($search_type_sort)->all();

        return $models;
    }


    public function showModels ($user_id, $type_sort_id)
    {
        $models = $this->fetchModels($user_id, $type_sort_id);

        $showModels = '';

        foreach ($models as $model) {

            $description = $model->description;
            if (mb_strlen($description) > 50) {
                $description = mb_substr($description, 0, 50) . '...';
            }

            $rid = $model->rid;

            if (mb_strlen($rid) > 80) {
                $rid = mb_substr($rid, 0, 80)  . ' ...';
            }

            $technology = $model->technology;

            if (mb_strlen($technology) > 50) {
                $technology = mb_substr($technology, 0, 50) . ' ...';
            }

            $showModels .= '<div class="row container-one_respond" style="margin: 3px 0; padding: 0;">
                                
                                <div class="col-md-3">
                                
                                    <div>
                                        '.Html::a(Html::encode($model->project_name), Url::to(['/segment/index', 'id' => $model->id]),[
                                            'class' => 'project_name_table_link'
                                        ]).'
                                    </div>
                                    
                                    <div class="project_description_text">
                                        <div title="'.$model->description.'">' . $description . '</div>
                                    </div>
                                    
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="text_14_table_project" title="' . $model->rid . '">' . $rid . '</div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="text_14_table_project" title="' . $model->technology . '">' . $technology . '</div>
                                </div>
                                
                                <div class="col-md-1 text-center">
                                    '.date('d.m.y', $model->created_at).'
                                </div>
                                
                                <div class="col-md-1 text-center">
                                    '.date('d.m.y', $model->updated_at).'
                                </div>
                                
                                <div class="col-md-2" style="padding-left: 20px; padding-right: 20px;">

                                    <div class="row" style="display:flex; align-items: center;">
                                    
                                        <div class="col-md-4">
                                            '.Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['#'], [
                                                'class' => '',
                                                'title' => 'Смотреть',
                                                'data-toggle' => 'modal',
                                                'data-target' => "#data_project_modal-$model->id",
                                            ]).'
                                        </div>
                                        
                                        <div class="col-md-4">
                                            '.Html::a(Html::img('/images/icons/update_warning_vector.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['#'], [
                                                'class' => '',
                                                'title' => 'Редактировать',
                                                'data-toggle' => 'modal',
                                                'data-target' => "#data_project_update_modal-$model->id",
                                            ]).'
                                        </div>
                                        
                                        <div class="col-md-4">
                                            '.Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['#'], [
                                                'class' => '',
                                                'title' => 'Удалить',
                                                'onclick' => 'return false',
                                            ]).'
                                        </div>
                                    
                                    </div>
                                    
                                </div>    
                                
                            </div>';
        }

        return $showModels;
    }

}