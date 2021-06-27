<?php


namespace app\models\forms;

use yii\base\Model;

class FormCreateQuestion extends Model
{

    public $list_questions;
    public $confirm_id;
    public $title;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_id', 'title'], 'required'],
            [['confirm_id'], 'integer'],
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
     * @param $id
     */
    public function setConfirmId($id)
    {
        $this->confirm_id = $id;
    }


    /**
     * @param $model
     * @return array|null
     */
    public function create($model)
    {
        $model->setParams(['confirm_id' => $this->confirm_id, 'title' => $this->title]);
        if ($model->save()){
            $confirm = $model->confirm;
            $questions = $confirm->questions;
            $queryQuestions = $model->confirm->queryQuestionsGeneralList();

            return ['model' => $model, 'questions' => $questions, 'queryQuestions' => $queryQuestions];
        }
        return null;
    }
}