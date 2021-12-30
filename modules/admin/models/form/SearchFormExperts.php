<?php


namespace app\modules\admin\models\form;

use app\models\ExpertType;
use app\models\User;
use yii\base\Model;
use yii\db\ActiveRecord;

class SearchFormExperts extends Model
{

    /**
     * ФИО эксперта
     * @var string
     */
    public $name;

    /**
     * Ключевые слова
     * @var string
     */
    public $keywords;

    /**
     * Тип эксперта
     * @var array
     */
    public $type;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['keywords', 'name'], 'string', 'max' => 255],
            [['keywords', 'name'], 'trim'],
            ['type', 'safe'],
        ];
    }


    /**
     * Поиск экспертов
     * @param $project_id
     * @return array|ActiveRecord[]
     */
    public static function search($project_id)
    {
        $filter = $_POST['SearchFormExperts'];
        $name = trim($filter['name']);
        $keywords = explode(' ', trim($filter['keywords']));
        if (!$filter['type']) {
            $listTypes = array_keys(ExpertType::getListTypes());
            $type = implode('|', $listTypes);
        } else {
            $type = implode('|', $filter['type']);
        }

        $experts = User::find()->joinWith(['expertInfo', 'keywords'])
            ->where(['role' => User::ROLE_EXPERT, 'status' => User::STATUS_ACTIVE])
            ->andWhere(['REGEXP', 'expert_info.type', $type])
            ->andWhere(['or',
                ['like', 'second_name', $name],
                ['like', 'first_name', $name],
                ['like', 'middle_name', $name],
                ['like', "CONCAT( second_name, ' ', first_name, ' ', middle_name)", $name],
                ['like', "CONCAT( second_name, ' ', middle_name, ' ', first_name)", $name],
                ['like', "CONCAT( first_name, ' ', middle_name, ' ', second_name)", $name],
                ['like', "CONCAT( first_name, ' ', second_name, ' ', middle_name)", $name],
                ['like', "CONCAT( middle_name, ' ', first_name, ' ', second_name)", $name],
                ['like', "CONCAT( middle_name, ' ', second_name, ' ', first_name)", $name],
            ]);

        foreach ($keywords as $keyword) {
            $experts->orOnCondition(['like', 'keywords_expert.description', $keyword]);
        }

        return $experts->all();
    }
}