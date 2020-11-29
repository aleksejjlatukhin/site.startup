<?php


namespace app\models\forms;

use yii\base\Model;
use app\models\User;
use app\models\Projects;
use app\models\Segment;
use app\models\GenerationProblem;
use app\models\Gcp;

class FormCreateGcp extends Model
{

    public $good;
    public $benefit;
    public $contrast;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['good', 'benefit', 'contrast'], 'trim'],
            [['good', 'contrast'], 'string', 'max' => 255],
            [['benefit'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'good' => 'Формулировка перспективного продукта',
            'benefit' => 'Какую выгоду дает использование данного продукта потребителю',
            'contrast' => 'По сравнению с каким продуктом заявлена выгода (с чем сравнивается)',
        ];
    }


    public function create($confirm_problem_id, $problem_id, $segment_id, $project_id)
    {

        $last_model = Gcp::find()->where(['confirm_problem_id' => $confirm_problem_id])->orderBy(['id' => SORT_DESC])->one();
        $project = Projects::findOne($project_id);
        $user = User::find()->where(['id' => $project->user_id])->one();
        $segment = Segment::findOne($segment_id);
        $generationProblem = GenerationProblem::findOne($problem_id);

        $gcp = new Gcp();
        $gcp->project_id = $project_id;
        $gcp->segment_id = $segment_id;
        $gcp->problem_id = $problem_id;
        $gcp->confirm_problem_id = $confirm_problem_id;
        $last_model_number = explode(' ',$last_model->title)[1];
        $gcp->title = 'ГЦП ' . ($last_model_number + 1);

        $gcp->description = 'Наш продукт ' . mb_strtolower($this->good) . ' ';
        $gcp->description .= 'помогает ' . mb_strtolower($segment->name) . ', ';
        $gcp->description .= 'который хочет удовлетворить проблему ' . mb_strtolower($generationProblem->description) . ', ';
        $gcp->description .= 'избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, ' . mb_strtolower($this->benefit) . ', ';
        $gcp->description .= 'в отличии от ' . mb_strtolower($this->contrast) . '.';

        if ($gcp->save()){

            $this->addDir($user, $project, $segment, $generationProblem, $gcp);

            return $gcp;

        }else {

            return null;
        }
    }



    private function addDir($user, $project, $segment, $generationProblem, $gcp)
    {

        $gcp_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/gcps/'
            . mb_convert_encoding($this->translit($gcp->title) , "windows-1251");

        $gcp_dir = mb_strtolower($gcp_dir, "windows-1251");

        if (!file_exists($gcp_dir)){
            mkdir($gcp_dir, 0777);
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