<?php


namespace app\models\forms;

use yii\base\Model;

class FormUpdateQuestion extends Model
{

    public $id;
    public $title;
    private $_question;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'title'], 'required'],
            [['id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'trim'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Описание вопроса',
        ];
    }


    /**
     * FormUpdateQuestion constructor.
     * @param $model
     * @param array $config
     */
    public function __construct($model, $config = [])
    {
        $this->_question = $model;
        $this->id = $model->id;
        $this->title = $model->title;
        parent::__construct($config);
    }


    /**
     * @return mixed
     */
    public function getConfirm()
    {
        return $this->_question->confirm;
    }


    /**
     * @return array|null
     */
    public function update()
    {
        $model = $this->_question;
        $model->title = $this->title;
        if ($model->save()) {
            $confirm = $model->confirm;
            $questions = $confirm->questions;
            $queryQuestions = $model->confirm->queryQuestionsGeneralList();

            return ['model' => $model, 'questions' => $questions, 'queryQuestions' => $queryQuestions];
        }
        return null;
    }

}