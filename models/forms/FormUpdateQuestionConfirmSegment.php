<?php


namespace app\models\forms;

use app\models\Questions;
use yii\base\Model;

class FormUpdateQuestionConfirmSegment extends Model
{

    public $id;
    public $interview_id;
    public $title;

    
    public function __construct($id, $config = [])
    {
        $question = Questions::findOne($id);
        $this->id = $question->id;
        $this->interview_id = $question->interview_id;
        $this->title = $question->title;
        parent::__construct($config);
    }


    public function update()
    {
        $question = Questions::findOne($this->id);
        $question->title = $this->title;
        return $question->save() ? $question : null;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'interview_id', 'title'], 'required'],
            [['id', 'interview_id'], 'integer'],
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