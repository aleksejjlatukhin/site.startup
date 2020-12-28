<?php


namespace app\models\forms;

use app\models\QuestionsConfirmGcp;
use yii\base\Model;

class FormUpdateQuestionConfirmGcp extends Model
{

    public $id;
    public $confirm_gcp_id;
    public $title;


    public function __construct($id, $config = [])
    {
        $question = QuestionsConfirmGcp::findOne($id);
        $this->id = $question->id;
        $this->confirm_gcp_id = $question->confirm_gcp_id;
        $this->title = $question->title;
        parent::__construct($config);
    }


    public function update()
    {
        $question = QuestionsConfirmGcp::findOne($this->id);
        $question->title = $this->title;
        return $question->save() ? $question : null;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'confirm_gcp_id', 'title'], 'required'],
            [['id', 'confirm_gcp_id'], 'integer'],
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