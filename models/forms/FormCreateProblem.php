<?php


namespace app\models\forms;

use app\models\GenerationProblem;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\base\Model;

class FormCreateProblem extends Model
{
    public $description;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'trim'],
            [['description'], 'string', 'max' => 255],
        ];
    }


    public function create($interview_id, $segment_id, $project_id)
    {
        $last_model = GenerationProblem::find()->where(['interview_id' => $interview_id])->orderBy(['id' => SORT_DESC])->one();
        $project = Projects::findOne($project_id);
        $user = User::find()->where(['id' => $project->user_id])->one();
        $segment = Segment::findOne($segment_id);

        $generationProblem = new GenerationProblem();
        $generationProblem->project_id = $project_id;
        $generationProblem->segment_id = $segment_id;
        $generationProblem->interview_id = $interview_id;
        $generationProblem->description = $this->description;
        $last_model_number = explode(' ',$last_model->title)[1];
        $generationProblem->title = 'ГПС ' . ($last_model_number + 1);

        if ($generationProblem->save()){

            $this->addDir($user, $project, $segment, $generationProblem);

            return $generationProblem;

        }else {

            return null;
        }
    }


    private function addDir($user, $project, $segment, $generationProblem)
    {
        $generation_problems_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
            mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/generation problems/';

        $generationProblem_dir = $generation_problems_dir . '/' . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/';
        $generationProblem_dir = mb_strtolower($generationProblem_dir, "windows-1251");

        if (!file_exists($generationProblem_dir)){
            mkdir($generationProblem_dir, 0777);
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