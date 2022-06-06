<?php


namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс хранит информацию в бд о всех вопросах,
 * которые добавлялись на этапе подтверждения гипотезы ценностного предложения
 *
 * Class AllQuestionsConfirmGcp
 * @package app\models
 *
 * @property int $id                            Идентификатор записи
 * @property string $title                      Описание вопроса
 * @property int $user_id                       Идентификатор пользователя, который добавил вопрос
 * @property int $created_at                    Дата создания
 */
class AllQuestionsConfirmGcp extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'all_questions_confirm_gcp';
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
            '1' => ['title' => 'Что понравилось в решении и что нет?'],
            '2' => ['title' => 'Вписывается ли предложение в формат вашей деятельности?'],
            '3' => ['title' => 'Что неудобно по сравнению с продуктами, которыми пользуются сейчас?'],
            '4' => ['title' => 'Какие важные аспекты в продукте не затронуты, которые следовало бы продумать?'],
            '5' => ['title' => 'Какая цена решения должна быть по вашему мнению?'],
            '6' => ['title' => 'Во сколько обходится решение этой проблемы?'],
            '7' => ['title' => 'Какой бюджет до этого выделяли?'],
            '8' => ['title' => 'Заплатили бы вы «X» рублей за продукт, который выполняет задачу «Y»?'],
            '9' => ['title' => 'Кто будет финансировать покупку?'],
            '10' => ['title' => 'С кем еще мне следует переговорить?'],
            '11' => ['title' => 'Решает ли ценностное предложенное вашу проблему?'],
            '12' => ['title' => 'Вы бы рассказали об этом ценностном предложении своим коллегам?'],
            '13' => ['title' => 'Вы бы попросили своего руководителя приобрести продукт, который реализует данное ценностное предложение?']
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