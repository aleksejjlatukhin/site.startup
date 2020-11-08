<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * This is the model class for table "segments".
 *
 * @property string $id
 * @property int $project_id
 * @property string $name
 * @property string $field_of_activity
 * @property string $sort_of_activity
 * @property string $age
 * @property string $income
 * @property string $quantity
 * @property string $market_volume
 * @property string $add_info
 */
class Segment extends \yii\db\ActiveRecord
{

    const TYPE_B2C = 100;
    const TYPE_B2B = 200;

    const GENDER_MAN = 50;
    const GENDER_WOMAN = 60;
    const GENDER_ANY = 70;

    const SECONDARY_EDUCATION = 50;
    const SECONDARY_SPECIAL_EDUCATION = 100;
    const HIGHER_INCOMPLETE_EDUCATION = 200;
    const HIGHER_EDUCATION = 300;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'segments';
    }

    public function getProject()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }

    public function getInterview()
    {
        return $this->hasOne(Interview::class, ['segment_id' => 'id']);
    }

    public function getProblems ()
    {
        return $this->hasMany(GenerationProblem::class, ['segment_id' => 'id']);
    }

    public function getGcps ()
    {
        return $this->hasMany(Gcp::class, ['segment_id' => 'id']);
    }

    public function getMvps ()
    {
        return $this->hasMany(Mvp::class, ['segment_id' => 'id']);
    }

    public function getBusinessModels ()
    {
        return $this->hasMany(BusinessModel::class, ['segment_id' => 'id']);
    }
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'time_confirm'], 'integer'],
            [['name', 'field_of_activity', 'sort_of_activity', 'add_info', 'description', 'specialization_of_activity'], 'trim'],
            [['project_id', 'type_of_interaction_between_subjects', 'gender_consumer', 'education_of_consumer', 'exist_confirm'], 'integer'],
            [['age_from', 'age_to'], 'integer'],
            [['income_from', 'income_to'], 'integer'],
            [['quantity_from', 'quantity_to'], 'integer'],
            [['market_volume'], 'integer'],
            [['add_info'], 'string'],
            [['name',], 'string', 'min' => 6, 'max' => 65],
            [['field_of_activity', 'sort_of_activity', 'specialization_of_activity', 'description', 'company_products', 'company_partner'], 'string', 'max' => 255],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'name' => 'Наименование сегмента',
            'description' => 'Краткое описание сегмента',
            'type_of_interaction_between_subjects' => 'Вид информационного и экономического взаимодействия между субъектами рынка',
            'field_of_activity' => 'Сфера деятельности потребителя',
            'sort_of_activity' => 'Вид деятельности потребителя',
            'specialization_of_activity' => 'Специализация вида деятельности потребителя',
            'age_from' => 'Возраст потребителя',
            'gender_consumer' => 'Пол потребителя',
            'education_of_consumer' => 'Образование потребителя',
            'income_from' => 'Доход потребителя (тыс. руб./мес.)',
            'quantity_from' => 'Потенциальное количество потребителей (тыс. чел.)',
            'market_volume' => 'Объем рынка (млн. руб./год)',
            'company_products' => 'Продукция / услуги предприятия',
            'company_partner' => 'Партнеры предприятия',
            'add_info' => 'Дополнительная информация',
        ];
    }


    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    public function deleteStage ()
    {

        if ($problems = $this->problems) {
            foreach ($problems as $problem) {
                $problem->deleteStage();
            }
        }

        if ($confirm = $this->interview) {

            $responds = $confirm->responds;
            foreach ($responds as $respond) {

                DescInterview::deleteAll(['respond_id' => $respond->id]);
            }

            Questions::deleteAll(['interview_id' => $confirm->id]);
            Respond::deleteAll(['interview_id' => $confirm->id]);
            $confirm->delete();
        }

        $this->delete();
    }
}
