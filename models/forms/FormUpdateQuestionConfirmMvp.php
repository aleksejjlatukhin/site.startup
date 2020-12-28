<?php


namespace app\models\forms;

use app\models\QuestionsConfirmMvp;
use yii\base\Model;

class FormUpdateQuestionConfirmMvp extends Model
{

    public $id;
    public $confirm_mvp_id;
    public $title;


    public function __construct($id, $config = [])
    {
        $question = QuestionsConfirmMvp::findOne($id);
        $this->id = $question->id;
        $this->confirm_mvp_id = $question->confirm_mvp_id;
        $this->title = $question->title;
        parent::__construct($config);
    }


    public function update()
    {
        $question = QuestionsConfirmMvp::findOne($this->id);
        $question->title = $this->title;
        return $question->save() ? $question : null;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'confirm_mvp_id', 'title'], 'required'],
            [['id', 'confirm_mvp_id'], 'integer'],
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