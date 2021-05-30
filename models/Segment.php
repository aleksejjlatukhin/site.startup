<?php

namespace app\models;

use Throwable;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;

class Segment extends ActiveRecord
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

    const EVENT_CLICK_BUTTON_CONFIRM = 'event click button confirm';

    public $propertyContainer;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'segments';
    }


    /**
     * Segment constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->propertyContainer = new PropertyContainer();

        parent::__construct($config);
    }


    /**
     * Получить объект проектв
     * @return ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }


    /**
     * Получить объект подтверждения
     * @return ActiveQuery
     */
    public function getInterview()
    {
        return $this->hasOne(Interview::class, ['segment_id' => 'id']);
    }


    /**
     * Получить все проблемы сегмента
     * @return ActiveQuery
     */
    public function getProblems ()
    {
        return $this->hasMany(GenerationProblem::class, ['segment_id' => 'id']);
    }


    /**
     * Получить все ЦП сегмента
     * @return ActiveQuery
     */
    public function getGcps ()
    {
        return $this->hasMany(Gcp::class, ['segment_id' => 'id']);
    }


    /**
     * Получить все Mv[ сегмента
     * @return ActiveQuery
     */
    public function getMvps ()
    {
        return $this->hasMany(Mvp::class, ['segment_id' => 'id']);
    }


    /**
     * Получить все бизнес-модели сегмента
     * @return ActiveQuery
     */
    public function getBusinessModels ()
    {
        return $this->hasMany(BusinessModel::class, ['segment_id' => 'id']);
    }


    /**
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->project_id;
    }
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'time_confirm'], 'integer'],
            [['name', 'field_of_activity', 'sort_of_activity', 'add_info', 'description'], 'trim'],
            [['project_id', 'type_of_interaction_between_subjects', 'gender_consumer', 'education_of_consumer', 'exist_confirm'], 'integer'],
            [['age_from', 'age_to'], 'integer'],
            [['income_from', 'income_to'], 'integer'],
            [['quantity_from', 'quantity_to'], 'integer'],
            [['market_volume'], 'integer'],
            [['add_info'], 'string'],
            [['name',], 'string', 'min' => 2, 'max' => 65],
            [['description', 'company_products', 'company_partner'], 'string', 'max' => 2000],
            [['field_of_activity', 'sort_of_activity'], 'string', 'max' => 255],
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
            'sort_of_activity' => 'Вид / специализация деятельности потребителя',
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


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    public function init()
    {
        $this->on(self::EVENT_CLICK_BUTTON_CONFIRM, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        parent::init();
    }

    /**
     * @throws Throwable
     * @throws ErrorException
     * @throws StaleObjectException
     */
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
                AnswersQuestionsConfirmSegment::deleteAll(['respond_id' => $respond->id]);
            }

            QuestionsConfirmSegment::deleteAll(['interview_id' => $confirm->id]);
            Respond::deleteAll(['interview_id' => $confirm->id]);
            $confirm->delete();
        }

        // Удаление директории сегмента
        $segmentPathDelete = UPLOAD.'/user-'.$this->project->user->id.'/project-'.$this->project->id.'/segments/segment-'.$this->id;
        if (file_exists($segmentPathDelete)) FileHelper::removeDirectory($segmentPathDelete);

        // Удаление кэша для форм сегмента
        $cachePathDelete = '../runtime/cache/forms/user-'.$this->project->user->id.'/projects/project-'.$this->project->id.'/segments/segment-'.$this->id;
        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

        // Удаление сегмента
        $this->delete();
    }
}
