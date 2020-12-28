<?php


namespace app\models\forms;

use app\models\QuestionsConfirmProblem;
use yii\base\Model;

class FormUpdateQuestionConfirmProblem extends Model
{

    public $id;
    public $confirm_problem_id;
    public $title;


    public function __construct($id, $config = [])
    {
        $question = QuestionsConfirmProblem::findOne($id);
        $this->id = $question->id;
        $this->confirm_problem_id = $question->confirm_problem_id;
        $this->title = $question->title;
        parent::__construct($config);
    }


    public function update()
    {
        $question = QuestionsConfirmProblem::findOne($this->id);
        $question->title = $this->title;
        return $question->save() ? $question : null;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'confirm_problem_id', 'title'], 'required'],
            [['id', 'confirm_problem_id'], 'integer'],
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
}