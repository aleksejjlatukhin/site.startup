<?php


namespace app\models\forms\expertise;


use app\models\Expertise;
use app\models\StageExpertise;
use app\models\TypesExpertAssessment;
use yii\base\Model;
use Yii;


/**
 * Форма создания экспертизы с множеством вопросов и одиним ответом для каждого вопроса
 *
 * Class FormExpertiseManyAnswer
 * @package app\models\forms\expertise
 *
 * @property $checkboxesPreparationInterviewQuality                 Оценки выбранные экспертом по вопросам качества подготовки интервью
 * @property $checkboxesConductingInterviewQuality                  Оценки выбранные экспертом по вопросам качества проведения интервью
 * @property $answerOptions                                         Объект stdClass с вариантами ответов
 * @property $comment                                               Комментарий к ответу в экспертизе
 */
class FormExpertiseManyAnswer extends Model
{

    /**
     * Принимает несколько значений,
     * по одному ответу на каждый вопрос,
     * который относится к качеству подготовки интервью
     */
    public $checkboxesPreparationInterviewQuality;

    /**
     * Принимает несколько значений,
     * по одному ответу на каждый вопрос,
     * который относится к качеству проведения интервью
     */
    public $checkboxesConductingInterviewQuality;

    /**
     * Объект stdClass с вариантами ответов
     *
     * @var object
     */
    protected $answerOptions = array();

    /**
     * Комментарий к ответу в экспертизе
     *
     * @var string
     */
    public $comment;

    /**
     * Свойство, которое хранит в себе сведения о экспертизе
     *
     * @var Expertise
     */
    public $_expertise;

    /**
     * FormCreateExpertiseAssessmentConsumerSettings constructor.
     * @param Expertise $expertise
     * @param array $config
     */
    public function __construct(Expertise $expertise, $config = [])
    {
        $this->_expertise = $expertise;
        $this->setAnswerOptions();
        $this->setCheckboxes($this->_expertise->getEstimation());
        $this->comment = $this->_expertise->getComment();

        parent::__construct($config);
    }

    /**
     * Получить объект stdClass с вариантами ответов
     *
     * @param null|string $key
     * @return array|object
     */
    public function getAnswerOptions($key = null)
    {
        if($key) {
            return json_decode(json_encode($this->answerOptions), true)[$key];
        }
        return $this->answerOptions;
    }

    /**
     * Установить массив с вариантами ответов
     */
    public function setAnswerOptions()
    {
        // Установить путь к файлу с вариантами ответов для формы
        $filename = StageExpertise::getList()[$this->_expertise->getStage()] . '.json';

        if (TypesExpertAssessment::getValue($this->_expertise->getTypeExpert()) == TypesExpertAssessment::ASSESSMENT_TECHNOLOGICAL_LEVEL) {
            $filePath = Yii::getAlias('@dirDataFormExpertise') . '/assessmentTechnologicalLevel/' . $filename;
        } elseif (TypesExpertAssessment::getValue($this->_expertise->getTypeExpert()) == TypesExpertAssessment::ASSESSMENT_CONSUMER_SETTINGS) {
            $filePath = Yii::getAlias('@dirDataFormExpertise') . '/assessmentConsumerSettings/' . $filename;
        }

        // Получить содержимое файла
        /** @var string $filePath */
        if ($filePath) {
            $file = file_get_contents($filePath);
            $this->answerOptions = json_decode($file);
        }
    }

    /**
     * Получить комментарий
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Установить комментарий
     *
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Установка значений чекбоксов из бд в форму
     *
     * @param string $estimationDB
     */
    public function setCheckboxes($estimationDB)
    {
        if (in_array($this->_expertise->getStage(), ([StageExpertise::CONFIRM_SEGMENT, StageExpertise::CONFIRM_PROBLEM, StageExpertise::CONFIRM_GCP, StageExpertise::CONFIRM_MVP]))) {

            $estimationStringPreparation = self::getStringBetween($estimationDB, 'checkboxesPreparationInterviewQuality(', ')');
            $estimationArrayPreparation = explode(';', $estimationStringPreparation);
            foreach ($estimationArrayPreparation as $i => $item) {
                $arr = explode(':', $item);
                $this->checkboxesPreparationInterviewQuality[$arr[0]] = [0 => $arr[1]];
            }

            $estimationStringConducting = self::getStringBetween($estimationDB, 'checkboxesConductingInterviewQuality(', ')');
            $estimationArrayConducting = explode(';', $estimationStringConducting);
            foreach ($estimationArrayConducting as $i => $item) {
                $arr = explode(':', $item);
                $this->checkboxesConductingInterviewQuality[$arr[0]] = [0 => $arr[1]];
            }
        }
    }

    /**
     * Получить подстроку между символами
     *
     * @param $str
     * @param $from
     * @param $to
     * @return false|string
     */
    public static function getStringBetween($str, $from, $to)
    {
        $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
        return substr($sub,0,strpos($sub,$to));
    }

    /**
     * Получить общее количество баллов по всем вопросам
     * по одной экспертизе одного эксперта
     *
     * @param string $estimationDB
     * @return int
     */
    public static function getGeneralEstimationByOne($estimationDB)
    {
        $sumEstimationPreparation = 0;
        $estimationStringPreparation = self::getStringBetween($estimationDB, 'checkboxesPreparationInterviewQuality(', ')');
        $estimationArrayPreparation = explode(';', $estimationStringPreparation);
        foreach ($estimationArrayPreparation as $i => $item) {
            $arr = explode(':', $item);
            $sumEstimationPreparation += (int)$arr[1];
        }

        $sumEstimationConducting = 0;
        $estimationStringConducting = self::getStringBetween($estimationDB, 'checkboxesConductingInterviewQuality(', ')');
        $estimationArrayConducting = explode(';', $estimationStringConducting);
        foreach ($estimationArrayConducting as $i => $item) {
            $arr = explode(':', $item);
            $sumEstimationConducting += (int)$arr[1];
        }

        return array_sum([$sumEstimationPreparation, $sumEstimationConducting]);
    }

    /**
     * Формирование данных оценки для записи в бд
     *
     * @return string
     */
    public function writeEstimation()
    {
        $estimation = 'checkboxesPreparationInterviewQuality(';
        foreach ($this->checkboxesPreparationInterviewQuality as $i => $item) {
            $estimation .= $i . ':' . $item[0] . ';';
        }
        $estimation .= ')checkboxesConductingInterviewQuality(';
        foreach ($this->checkboxesConductingInterviewQuality as $i => $item) {
            $estimation .= $i . ':' . $item[0] . ';';
        }
        $estimation .= ')';

        return $estimation;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'string', 'max' => 2000],
            [['comment'], 'trim'],
            [['comment'], 'required'],
            ['checkboxesPreparationInterviewQuality', 'validateCheckboxesPreparation'],
            ['checkboxesConductingInterviewQuality', 'validateCheckboxesConducting']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'comment' => 'Напишите комментарий'
        ];
    }


    /**
     * Проверка на то, что эксперт ответил на все вопросы по качеству подготовки интервью
     *
     * @param $attr
     */
    public function validateCheckboxesPreparation($attr)
    {
        foreach ($this->checkboxesPreparationInterviewQuality as $item){
            if (!$item) {
                $this->addError($attr, 'Необходимо ответить на все вопросы экспертизы, которое касаются качества подготовки интервью');
            }
        }
    }


    /**
     * Проверка на то, что эксперт ответил на все вопросы по качеству проведения интервью
     *
     * @param $attr
     */
    public function validateCheckboxesConducting($attr)
    {
        foreach ($this->checkboxesConductingInterviewQuality as $item){
            if (!$item) {
                $this->addError($attr, 'Необходимо ответить на все вопросы экспертизы, которое касаются качества проведения интервью');
            }
        }
    }


    /**
     * Сохранение экспертизы, если передан парааметр
     * $completed, то экспертиза будет завершена и
     * будут отправлены коммуникации (уведомления)
     * трекеру и проектанту
     *
     * @param bool $completed
     * @return bool
     */
    public function saveRecord($completed = false)
    {
        $this->_expertise->setEstimation($this->writeEstimation());
        $this->_expertise->setComment($this->comment);
        $completed ? $this->_expertise->setCompleted() : false;
        return $this->_expertise->saveRecord();
    }
}