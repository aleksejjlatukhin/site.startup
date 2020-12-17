<?php


namespace app\models\forms;

use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Mvp;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\base\Model;

class FormCreateMvp extends Model
{

    public $description;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'trim'],
            [['description'], 'string', 'max' => 2000],
        ];
    }


    public function create($condirm_gcp_id, $gcp_id, $problem_id, $segment_id, $project_id)
    {
        $last_model = Mvp::find()->where(['confirm_gcp_id' => $condirm_gcp_id])->orderBy(['id' => SORT_DESC])->one();
        $project = Projects::findOne($project_id);
        $user = User::find()->where(['id' => $project->user_id])->one();
        $segment = Segment::findOne($segment_id);
        $generationProblem = GenerationProblem::findOne($problem_id);
        $gcp = Gcp::findOne($gcp_id);

        $mvp = new Mvp();
        $mvp->project_id = $project->id;
        $mvp->segment_id = $segment->id;
        $mvp->problem_id = $generationProblem->id;
        $mvp->gcp_id = $gcp->id;
        $mvp->confirm_gcp_id = $condirm_gcp_id;
        $last_model_number = explode(' ',$last_model->title)[1];
        $mvp->title = 'MVP ' . ($last_model_number + 1);
        $mvp->description = $this->description;

        if ($mvp->save()){

            $this->addDir($user, $project, $segment, $generationProblem, $gcp, $mvp);

            return $mvp;

        }else {

            return null;
        }

    }


    public function addDir($user, $project, $segment, $generationProblem, $gcp, $mvp)
    {

        $mvp_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/gcps/'
            . mb_convert_encoding($this->translit($gcp->title) , "windows-1251") . '/mvps/'
            . mb_convert_encoding($this->translit($mvp->title) , "windows-1251");

        $mvp_dir = mb_strtolower($mvp_dir, "windows-1251");

        if (!file_exists($mvp_dir)){
            mkdir($mvp_dir, 0777);
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