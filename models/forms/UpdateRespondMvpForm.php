<?php


namespace app\models\forms;

use app\models\ConfirmMvp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Mvp;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\base\Model;
use app\models\RespondsMvp;

class UpdateRespondMvpForm extends Model
{

    public $id;
    public $confirm_mvp_id;
    public $name;
    public $info_respond;
    public $place_interview;
    public $email;
    public $date_plan;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'info_respond', 'place_interview'], 'required'],
            [['name', 'info_respond', 'email', 'place_interview'], 'trim'],
            [['date_plan'], 'safe'],
            [['name'], 'uniqueName'],
            [['name'], 'string', 'max' => 100],
            [['info_respond', 'email', 'place_interview'], 'string', 'max' => 255],
            ['email', 'email', 'message' => 'Неверный формат адреса электронной почты'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Респондент',
            'info_respond' => 'Данные респондента',
            'email' => 'E-mail',
            'date_plan' => 'Плановая дата интервью',
            'place_interview' => 'Место проведения интервью',
        ];
    }


    public function __construct($id, $config = [])
    {
        $respond = RespondsMvp::findOne($id);
        $this->id = $id;
        $this->confirm_mvp_id = $respond->confirm_mvp_id;
        $this->name = $respond->name;
        $this->info_respond = $respond->info_respond;
        $this->email = $respond->email;
        $this->place_interview = $respond->place_interview;
        $this->date_plan = $respond->date_plan;
        parent::__construct($config);
    }


    public function updateRespond()
    {
        $respond = RespondsMvp::findOne($this->id);
        $respond->name = $this->name;
        $respond->info_respond = $this->info_respond;
        $respond->place_interview = $this->place_interview;
        $respond->email = $this->email;
        $respond->date_plan = strtotime($this->date_plan);
        $this->addDirOrUpdateDir();
        return $respond->save() ? $respond : null;
    }


    private function addDirOrUpdateDir()
    {
        $models = RespondsMvp::findAll(['confirm_mvp_id' => $this->confirm_mvp_id]);
        $confirmMvp = ConfirmMvp::findOne(['id' => $this->confirm_mvp_id]);
        $mvp = Mvp::findOne(['id' => $confirmMvp->mvp_id]);
        $gcp = Gcp::findOne(['id' => $mvp->gcp_id]);
        $problem = GenerationProblem::findOne(['id' => $mvp->problem_id]);
        $segment = Segment::findOne(['id' => $mvp->segment_id]);
        $project = Projects::findOne(['id' => $mvp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        foreach ($models as $elem){

            if ($this->id == $elem->id && mb_strtolower(str_replace(' ', '',$this->name)) != mb_strtolower(str_replace(' ', '',$elem->name))){

                $old_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                    mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                    mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'.
                    mb_convert_encoding($this->translit($problem->title) , "windows-1251") .'/gcps/'.
                    mb_convert_encoding($this->translit($gcp->title) , "windows-1251") .'/mvps/'.
                    mb_convert_encoding($this->translit($mvp->title) , "windows-1251") .'/interviews/'.
                    mb_convert_encoding($this->translit($elem->name) , "windows-1251") . '/';

                $new_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                    mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                    mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'.
                    mb_convert_encoding($this->translit($problem->title) , "windows-1251") .'/gcps/'.
                    mb_convert_encoding($this->translit($gcp->title) , "windows-1251") .'/mvps/'.
                    mb_convert_encoding($this->translit($mvp->title) , "windows-1251") .'/interviews/'.
                    mb_convert_encoding($this->translit($this->name) , "windows-1251") . '/';

                if (file_exists($old_dir)){
                    rename($old_dir, $new_dir);
                }
            }
        }

        $interviews_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($this->translit($problem->title) , "windows-1251") .'/gcps/'.
            mb_convert_encoding($this->translit($gcp->title) , "windows-1251") .'/mvps/'.
            mb_convert_encoding($this->translit($mvp->title) , "windows-1251") .'/interviews/';
        if (!file_exists($interviews_dir)){
            mkdir($interviews_dir, 0777);
        }

        $respond_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($this->translit($problem->title) , "windows-1251") .'/gcps/'.
            mb_convert_encoding($this->translit($gcp->title) , "windows-1251") .'/mvps/'.
            mb_convert_encoding($this->translit($mvp->title) , "windows-1251") .'/interviews/' .
            mb_convert_encoding($this->translit($this->name) , "windows-1251") . '/';
        if (!file_exists($respond_dir)){
            mkdir($respond_dir, 0777);
        }
    }


    /**
     * @param $attr
     */
    public function uniqueName($attr)
    {
        $models = RespondsMvp::findAll(['confirm_mvp_id' => $this->confirm_mvp_id]);

        foreach ($models as $item){

            if ($this->id != $item->id && mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }

    /**
     * @param $s
     * @return false|string|string[]|null
     */
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