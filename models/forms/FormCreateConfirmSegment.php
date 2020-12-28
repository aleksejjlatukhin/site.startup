<?php


namespace app\models\forms;

use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\base\Model;

class FormCreateConfirmSegment extends Model
{
    public $segment_id;
    public $count_respond;
    public $count_positive;
    public $greeting_interview;
    public $view_interview;
    public $reason_interview;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['segment_id', 'count_respond', 'count_positive', 'greeting_interview', 'view_interview', 'reason_interview'], 'required'],
            [['segment_id'], 'integer'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'max' => '100'],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'string', 'max' => '2000'],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'trim'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'segment_id' => 'Segment ID',
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Количество респондентов, соответствующих сегменту',
            'greeting_interview' => 'Приветствие в начале встречи',
            'view_interview' => 'Информация о вас для респондентов',
            'reason_interview' => 'Как вы объясните ваш интерес (причину) к интервью респонденту',
        ];
    }


    public function create ()
    {
        $model = new Interview();
        $model->segment_id = $this->segment_id;
        $model->count_respond = $this->count_respond;
        $model->count_positive = $this->count_positive;
        $model->greeting_interview = $this->greeting_interview;
        $model->view_interview = $this->view_interview;
        $model->reason_interview = $this->reason_interview;
        $this->addDir();

        return $model->save() ? $model : null;
    }


    public function addDir ()
    {
        $segment = Segment::findOne($this->segment_id);
        $project = Projects::findOne($segment->project_id);
        $user = User::findOne($project->user_id);

        $interviews_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
            mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/interviews/';
        if (!file_exists($interviews_dir)) {
            mkdir($interviews_dir, 0777);
        }

        $generation_problems_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
            mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/generation problems/';
        if (!file_exists($generation_problems_dir)) {
            mkdir($generation_problems_dir, 0777);
        }
    }


    public function translit($s)
    {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат

    }
}