<?php


namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class QuestionsConfirmMvp extends ActiveRecord
{

    public $list_questions;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questions_confirm_mvp';
    }


    /**
     * Получить объект подтверждения
     * @return ActiveQuery
     */
    public function getConfirm ()
    {
        return $this->hasOne(ConfirmMvp::class, ['id' => 'confirm_mvp_id']);
    }


    /**
     * Получить все ответы на данный вопрос
     * @return array|ActiveRecord[]
     */
    public function getAnswers()
    {
        $answers = AnswersQuestionsConfirmMvp::find()->where(['question_id' => $this->id])
            ->andWhere(['not', ['answers_questions_confirm_mvp.answer' => '']])->all();
        return $answers;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_mvp_id', 'title'], 'required'],
            [['confirm_mvp_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'list_questions'], 'string', 'max' => 255],
            [['title'], 'trim'],
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
            TimestampBehavior::class
        ];
    }


    public function init()
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->confirm->mvp->project->touch('updated_at');
            $this->confirm->mvp->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->confirm->mvp->project->touch('updated_at');
            $this->confirm->mvp->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->confirm->mvp->project->touch('updated_at');
            $this->confirm->mvp->project->user->touch('updated_at');
        });

        parent::init();
    }

}