<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "mvp".
 *
 * @property string $id
 * @property int $confirm_gcp_id
 * @property string $title
 * @property string $description
 * @property string $date_create
 * @property string $date_confirm
 * @property int $exist_confirm
 */
class Mvp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mvp';
    }

    public function getConfirm()
    {
        return $this->hasOne(ConfirmMvp::class, ['mvp_id' => 'id']);
    }

    public function getProject ()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }

    public function getSegment ()
    {
        return $this->hasOne(Segment::class, ['id' => 'segment_id']);
    }

    public function getProblem ()
    {
        return $this->hasOne(GenerationProblem::class, ['id' => 'problem_id']);
    }

    public function getGcp ()
    {
        return $this->hasOne(Gcp::class, ['id' => 'gcp_id']);
    }

    public function getBusinessModel ()
    {
        return $this->hasOne(BusinessModel::class, ['mvp_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_gcp_id', 'title', 'description'], 'required'],
            [['title', 'description'], 'trim'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['time_confirm', 'confirm_gcp_id', 'exist_confirm', 'project_id', 'segment_id', 'problem_id', 'gcp_id', 'created_at', 'updated_at'], 'integer'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'confirm_gcp_id' => 'Confirm Gcp ID',
            'title' => 'Наименование ГMVP',
            'description' => 'Описание',
        ];
    }

    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
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
        $mvps_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/gcps/'
            . mb_convert_encoding($this->translit($gcp->title) , "windows-1251") . '/mvps/';

        $mvps_dir = mb_strtolower($mvps_dir, "windows-1251");

        if (!file_exists($mvps_dir)){
            mkdir($mvps_dir, 0777);
        }

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


    public function deleteStage ()
    {
        if ($businessModel = $this->businessModel) {
            $businessModel->delete();
        }

        if ($confirm = $this->confirm) {

            $responds = $confirm->responds;
            foreach ($responds as $respond) {

                DescInterviewMvp::deleteAll(['responds_mvp_id' => $respond->id]);
                AnswersQuestionsConfirmMvp::deleteAll(['respond_id' => $respond->id]);
            }

            QuestionsConfirmMvp::deleteAll(['confirm_mvp_id' => $confirm->id]);
            RespondsMvp::deleteAll(['confirm_mvp_id' => $confirm->id]);
            $confirm->delete();
        }

        $this->delete();
    }
}
