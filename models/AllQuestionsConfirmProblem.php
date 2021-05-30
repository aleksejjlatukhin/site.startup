<?php


namespace app\models;

use yii\db\ActiveRecord;

class AllQuestionsConfirmProblem extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'all_questions_confirm_problem';
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
            '2' => ['title' => 'Случалось ли вам столкнуться с …?'],
            '3' => ['title' => 'Попадали ли вы в ситуацию ..?'],
            '4' => ['title' => 'Как часто с вами происходит ..?'],
            '5' => ['title' => 'Когда вы последний раз оказывались в ситуации ..?'],
            '6' => ['title' => 'Как на вашу жизнь влияет ..?'],
            '7' => ['title' => 'Какие трудности у вас вызывает это решение?'],
            '8' => ['title' => 'Что вас не устраивает в нынешнем решении?'],
            '9' => ['title' => 'Почему вы поступили именно так?'],
            '10' => ['title' => 'Почему вас это беспокоит?'],
            '11' => ['title' => 'Каковы последствия этой ситуации?'],
            '12' => ['title' => 'С кем еще мне следует переговорить?'],
            '13' => ['title' => 'Есть ли еще вопросы, которые мне следовало задать?']
        ];

        return $array;
    }
}