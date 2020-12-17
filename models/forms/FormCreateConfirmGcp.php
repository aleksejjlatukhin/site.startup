<?php


namespace app\models\forms;

use app\models\ConfirmGcp;
use app\models\Gcp;
use app\models\User;
use yii\base\Model;

class FormCreateConfirmGcp extends Model
{

    public $gcp_id;
    public $count_respond;
    public $count_positive;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gcp_id', 'count_respond', 'count_positive'], 'required'],
            [['gcp_id'], 'integer'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'max' => '100'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'count_respond' => 'Количество респондентов, подтвердивших проблему',
            'count_positive' => 'Необходимое количество респондентов, подтверждающих ценностное предложение',
        ];
    }


    public function create()
    {
        $model = new ConfirmGcp();
        $model->gcp_id = $this->gcp_id;
        $model->count_respond = $this->count_respond;
        $model->count_positive = $this->count_positive;
        $this->addDir();

        return $model->save() ? $model : null;
    }


    private function addDir()
    {
        $gcp = Gcp::findOne(['id' => $this->gcp_id]);
        $problem = $gcp->problem;
        $segment = $gcp->segment;
        $project = $gcp->project;
        $user = User::findOne($project->user_id);

        $mvps_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($this->translit($problem->title) , "windows-1251") . '/gcps/'
            . mb_convert_encoding($this->translit($gcp->title) , "windows-1251") . '/mvps/';

        $mvps_dir = mb_strtolower($mvps_dir, "windows-1251");

        if (!file_exists($mvps_dir)){
            mkdir($mvps_dir, 0777);
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