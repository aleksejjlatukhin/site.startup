<?php


namespace app\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class AllQuestions extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'all_questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'user_id', 'field_of_activity', 'sort_of_activity', 'specialization_of_activity'], 'required'],
            [['title', 'field_of_activity', 'sort_of_activity', 'specialization_of_activity'], 'string', 'max' => 255],
            [['title', 'field_of_activity', 'sort_of_activity', 'specialization_of_activity'], 'trim'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            ['type_of_interaction_between_subjects', 'default', 'value' => Segment::TYPE_B2C],
            ['type_of_interaction_between_subjects',  'in', 'range' => [
                Segment::TYPE_B2C,
                Segment::TYPE_B2B
            ]]
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Описание вопроса',
            'type_of_interaction_between_subjects' => 'Вид информационного и экономического взаимодействия между субъектами рынка',
            'field_of_activity' => 'Сфера деятельности потребителя',
            'sort_of_activity' => 'Вид деятельности потребителя',
            'specialization_of_activity' => 'Специализация вида деятельности потребителя',
        ];
    }

    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }
}