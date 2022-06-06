<?php


namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс хранит информацию в бд о всех вопросах,
 * которые добавлялись на этапе подтверждения гипотезы сегмента
 *
 * Class AllQuestionsConfirmSegment
 * @package app\models
 *
 * @property int $id                            Идентификатор записи
 * @property string $title                      Описание вопроса
 * @property int $user_id                       Идентификатор пользователя, который добавил вопрос
 * @property int $created_at                    Дата создания
 */
class AllQuestionsConfirmSegment extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'all_questions_confirm_segment';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'user_id'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'trim'],
            [['user_id', 'created_at'], 'integer'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ['title' => 'Описание вопроса'];
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']],
            ],
        ];
    }


    /**
     * Вопросы по-умолчанию
     * @return array
     */
    public static function defaultListQuestions()
    {
        $array = [
            '0' => ['title' => 'Чем вы занимаетесь в настоящее время?'],
            '1' => ['title' => 'На каком этапе проекта вы находитесь?'],
            '2' => ['title' => 'Что получается и что не получается в вашем проекте? Приведите примеры.'],
            '3' => ['title' => 'Как вы определяете цели, задачи и последовательность действий?'],
            '4' => ['title' => 'Как вы добиваетесь достижения поставленной цели?'],
            '5' => ['title' => 'Что пытались сделать, чтобы определить верные последовательные действия?'],
            '6' => ['title' => 'Как вы решали проблему в последний раз, какие шаги предпринимали?'],
            '7' => ['title' => 'Как и посредством какого инструмента / процесса вы справляетесь с задачей?'],
            '8' => ['title' => 'Что не нравится в текущем положении вещей?'],
            '9' => ['title' => 'Что вы пытались с этим сделать?'],
            '10' => ['title' => 'Если ничего не делали, то почему?'],
            '11' => ['title' => 'Сколько денег / времени на это тратится сейчас?'],
            '12' => ['title' => 'Что влияет на решение о покупке продукта?'],
            '13' => ['title' => 'Как принимается решение о покупке?'],
            '14' => ['title' => 'Расскажите, что произойдет, если вы не сможете решать потребность? Что при решении доставляет вам неудобство?'],
            '15' => ['title' => 'Расскажите, пожалуйста, про последний раз, когда вы сталкивались с этими сложностями. Почему это было тяжело?']
        ];

        return $array;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}