<?php


namespace app\models;

use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;

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
        '8' => ['id' => '9', 'parent_id' => '1', 'name' => 'сначала старые', 'type_sort' => ['created_at' => SORT_ASC]],
        '9' => ['id' => '10', 'parent_id' => '1', 'name' => 'сначала новые', 'type_sort' => ['created_at' => SORT_DESC]],
        '10' => ['id' => '11', 'parent_id' => '2', 'name' => 'сначала ожидающие подтверждения', 'type_sort' => ['exist_confirm' => SORT_ASC]],
        '11' => ['id' => '12', 'parent_id' => '2', 'name' => 'сначала подтвержденные', 'type_sort' => ['exist_confirm' => SORT_DESC]],
        '12' => ['id' => '13', 'parent_id' => '2', 'name' => 'сначала неподтвержденные', 'type_sort' => ['exist_confirm' => SORT_ASC]],
        '13' => ['id' => '14', 'parent_id' => '3', 'name' => 'по алфавиту - от а до я', 'type_sort' => ['name' => SORT_ASC]],
        '14' => ['id' => '15', 'parent_id' => '3', 'name' => 'по алфавиту - от я до а', 'type_sort' => ['name' => SORT_DESC]],
        '15' => ['id' => '16', 'parent_id' => '4', 'name' => 'сначала по типу B2C', 'type_sort' => ['type_of_interaction_between_subjects' => SORT_ASC]],
        '16' => ['id' => '17', 'parent_id' => '4', 'name' => 'сначала по типу B2B', 'type_sort' => ['type_of_interaction_between_subjects' => SORT_DESC]],
        '17' => ['id' => '18', 'parent_id' => '5', 'name' => 'по алфавиту - от а до я', 'type_sort' => ['field_of_activity' => SORT_ASC]],
        '18' => ['id' => '19', 'parent_id' => '5', 'name' => 'по алфавиту - от я до а', 'type_sort' => ['field_of_activity' => SORT_DESC]],
        '19' => ['id' => '20', 'parent_id' => '6', 'name' => 'по алфавиту - от а до я', 'type_sort' => ['sort_of_activity' => SORT_ASC]],
        '20' => ['id' => '21', 'parent_id' => '6', 'name' => 'по алфавиту - от я до а', 'type_sort' => ['sort_of_activity' => SORT_DESC]],
        '21' => ['id' => '22', 'parent_id' => '7', 'name' => 'по алфавиту - от а до я', 'type_sort' => ['specialization_of_activity' => SORT_ASC]],
        '22' => ['id' => '23', 'parent_id' => '7', 'name' => 'по алфавиту - от я до а', 'type_sort' => ['specialization_of_activity' => SORT_DESC]],
        '23' => ['id' => '24', 'parent_id' => '8', 'name' => 'по возрастанию', 'type_sort' => ['market_volume' => SORT_ASC]],
        '24' => ['id' => '25', 'parent_id' => '8', 'name' => 'по убыванию', 'type_sort' => ['market_volume' => SORT_DESC]],

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


    private function fetchModels ($project_id, $type_sort_id)
    {
        $array_sort = self::$array;

        $key_arr = array_search($type_sort_id, array_column($array_sort, 'id'));

        $search_type_sort = $array_sort[$key_arr]['type_sort'];

        /*Для того чтобы вывести значения в порядке [0, 1, null] */
        if ($type_sort_id == 13){
            $models_not_null = Segment::find()->where(['project_id' => $project_id])->andWhere(['is not', 'exist_confirm', null])->orderBy($search_type_sort)->all();
            $models_is_null = Segment::find()->where(['project_id' => $project_id])->andWhere(['is', 'exist_confirm', null])->orderBy($search_type_sort)->all();
            $models = array_merge($models_not_null, $models_is_null);
            return $models;
        }

        $models = Segment::find()->where(['project_id' => $project_id])->orderBy($search_type_sort)->all();

        return $models;
    }


    public function showModels ($project_id, $type_sort_id)
    {
        $models = $this->fetchModels($project_id, $type_sort_id);

        $showModels = '';

        foreach ($models as $model) {

            /*статус подтверждения сегмента*/
            if ($model->exist_confirm === 1) {

                $status_confirm = '<div class="text-center" style="padding: 0 5px;">' . Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

            }elseif ($model->exist_confirm === null && empty($model->interview)) {

                $status_confirm = '<div class="text-center" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

            }elseif ($model->exist_confirm === null && !empty($model->interview)) {

                $status_confirm = '<div class="text-center" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

            }elseif ($model->exist_confirm === 0) {

                $status_confirm = '<div class="text-center" style="padding: 0 5px;">' . Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

            }

            /*наименование сегмента*/
            if ($model->interview) {

                $segment_name = Html::a(Html::encode($model->name), Url::to(['/interview/view', 'id' => $model->interview->id]), [
                    'title' => 'Переход к программе генерации ГПС', 'class' => 'container-name_link',
                ]);

            } else {

                $segment_name = Html::a(Html::encode($model->name), Url::to(['/interview/create', 'id' => $model->id]), [
                    'title' => 'Создание программы генерации ГПС', 'class' => 'container-name_link',
                ]);
            }

            /*тип сегмента*/
            if ($model->type_of_interaction_between_subjects === Segment::TYPE_B2C) {
                $type_segment = '<div class="">B2C</div>';
            }
            elseif ($model->type_of_interaction_between_subjects === Segment::TYPE_B2B) {
                $type_segment = '<div class="">B2B</div>';
            }

            /*сфера деятельности*/
            $field_of_activity = $model->field_of_activity;

            if (mb_strlen($field_of_activity) > 50) {
                $field_of_activity = mb_substr($field_of_activity, 0, 50);
                $field_of_activity = $field_of_activity . ' ...';
            }

            /*вид деятельности*/
            $sort_of_activity = $model->sort_of_activity;

            if (mb_strlen($sort_of_activity) > 50) {
                $sort_of_activity = mb_substr($sort_of_activity, 0, 50);
                $sort_of_activity = $sort_of_activity . ' ...';
            }

            /*специализация*/
            $specialization_of_activity = $model->specialization_of_activity;

            if (mb_strlen($specialization_of_activity) > 50) {
                $specialization_of_activity = mb_substr($specialization_of_activity, 0, 50);
                $specialization_of_activity = $specialization_of_activity . ' ...';
            }


            $showModels .= '<div class="row container-one_hypothesis" style="margin: 3px 0; padding: 0;">

                                <div class="col-md-3">
                                
                                    <div class="row" style="display:flex; align-items: center;">
                                    
                                        <div class="col-md-1" style="padding-bottom: 3px;">
                                        
                                            '.$status_confirm.'
                                        
                                        </div>
                                        
                                        <div class="col-md-8">
                                        
                                            '.$segment_name.'
                                        
                                        </div>
                                        
                                        <div class="col-md-3 text-center">
                                        
                                            '.$type_segment.'
                                        
                                        </div>
                                    
                                    </div>
                                
                                </div>
                                
                                <div class="col-md-2">
                                
                                    <div title="' . $model->field_of_activity . '">' . $field_of_activity . '</div>
                                
                                </div>
                                
                                <div class="col-md-2">
                                
                                    <div title="' . $model->sort_of_activity . '">' . $sort_of_activity . '</div>
                                
                                </div>
                                
                                <div class="col-md-2">
                                
                                    <div title="' . $model->specialization_of_activity . '">' . $specialization_of_activity . '</div>
                                
                                </div>
                                
                                <div class="col-md-3" style="padding: 0;">

                                    <div class="row" style="display:flex; align-items: center;">
                                    
                                        <div class="col-md-4 text-right" style="font-size: 16px;">
                                        
                                            '.number_format($model->market_volume, 0, '', ' ').'
                                        
                                        </div>
                                        
                                        <div class="col-md-1"></div>

                                        <div class="col-md-2">
                                        
                                            '.Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['/segment/view', 'id' => $model->id], [
                                                'class' => '',
                                                'title' => 'Смотреть',
                                                'data-toggle' => 'modal',
                                                'data-target' => "#segment_view_modal-$model->id",
                                            ]).'
                                        
                                        </div>
                                        
                                        <div class="col-md-2">
                                        
                                            '.Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['/segment/update', 'id' => $model->id], [
                                                'class' => '',
                                                'title' => 'Редактировать',
                                                'data-toggle' => 'modal',
                                                'data-target' => "#update_segment_modal-$model->id",
                                            ]).'
                                        
                                        </div>
                                        
                                        <div class="col-md-2">
                                        
                                            '.Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['#'], [
                                                'class' => '',
                                                'title' => 'Удалить',
                                                'onclick' => 'return false',
                                            ]).'
                                        
                                        </div>
                                        
                                        <div class="col-md-1"></div>
                                    
                                    </div>
                                    
                                </div>
                                
                            </div>';
        }

        return $showModels;
    }


}