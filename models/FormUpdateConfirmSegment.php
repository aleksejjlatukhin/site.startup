<?php


namespace app\models;

use yii\base\Model;

class FormUpdateConfirmSegment extends Model
{
    public $id;
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
            [['segment_id'], 'integer'],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'trim'],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'string', 'max' => 255],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'segment_id' => 'Segment ID',
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Количество респондентов, соответствующих сегменту',
            'greeting_interview' => 'Приветствие в начале встречи',
            'view_interview' => 'Представление интервьюера',
            'reason_interview' => 'Почему мне интересно',
        ];
    }

    public function __construct($id, $config = [])
    {
        $confirm_segment = Interview::findOne($id);

        $this->id = $id;
        $this->segment_id = $confirm_segment->segment_id;
        $this->count_respond = $confirm_segment->count_respond;
        $this->count_positive = $confirm_segment->count_positive;
        $this->greeting_interview = $confirm_segment->greeting_interview;
        $this->view_interview = $confirm_segment->view_interview;
        $this->reason_interview = $confirm_segment->reason_interview;

        parent::__construct($config);
    }

    public function update()
    {

        if ($this->validate()) {

            $confirm_segment = Interview::findOne($this->id);
            $confirm_segment->count_respond = $this->count_respond;
            $confirm_segment->count_positive = $this->count_positive;
            $confirm_segment->greeting_interview = $this->greeting_interview;
            $confirm_segment->view_interview = $this->view_interview;
            $confirm_segment->reason_interview = $this->reason_interview;

            $this->updateCountResponds();

            return $confirm_segment->save() ? $confirm_segment : null;
        }
        return false;
    }


    //Редактирование числа респондентов (Шаг 1)
    private function updateCountResponds ()
    {
        $segment = Segment::findOne($this->segment_id);
        $project = Projects::findOne($segment->project_id);
        $user = User::findOne($project->user_id);
        $responds = Respond::find()->where(['interview_id' => $this->id])->all();

        if ((count($responds)+1) <= $this->count_respond){
            for ($count = count($responds) + 1; $count <= $this->count_respond; $count++ )
            {
                $newRespond[$count] = new Respond();
                $newRespond[$count]->interview_id = $this->id;
                $newRespond[$count]->name = 'Респондент ' . $count;
                $newRespond[$count]->save();
            }
        }else{
            $minus = count($responds) - $this->count_respond;
            $respond = Respond::find()->where(['interview_id' => $this->id])->orderBy(['id' => SORT_DESC])->limit($minus)->all();
            foreach ($respond as $item)
            {
                $descInterview = DescInterview::find()->where(['respond_id' => $item->id])->one();

                if ($descInterview) {
                    $descInterview->delete();
                }

                $del_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                    mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                    mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/interviews/' .
                    mb_convert_encoding($this->translit($item->name), "windows-1251") . '/';

                if (file_exists($del_dir)) {
                    $this->delTree($del_dir);
                }

                $item->delete();
            }
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

    public function delTree($dir)
    {
        if ($objs = glob($dir."/*"))
        {
            foreach($objs as $obj)
            {
                is_dir($obj) ? $this->delTree($obj) : unlink($obj);
            }
        }
        rmdir($dir);

    }

}