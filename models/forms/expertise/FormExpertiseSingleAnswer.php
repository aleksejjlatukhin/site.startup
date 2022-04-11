<?php


namespace app\models\forms\expertise;


use app\models\Expertise;
use app\models\StageExpertise;
use app\models\TypesExpertAssessment;
use yii\base\Model;
use Yii;


/**
 * Форма создания экспертизы с выбором единственного ответа
 *
 * Class FormExpertiseSingleAnswer
 * @package app\models\forms\expertise
 *
 * @property $checkbox              Оценка выбранная экспертом
 * @property $answerOptions         Объект stdClass с вариантами ответов
 * @property $comment               Комментарий к ответу в экспертизе
 */
class FormExpertiseSingleAnswer extends Model
{

    /**
     * Принимает единственное значение
     * из предложенных вариантов ответа
     */
    public $checkbox;

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
        $this->checkbox = $this->_expertise->getEstimation();
        $this->comment = $this->_expertise->getComment();

        parent::__construct($config);
    }

    /**
     * Получить объект stdClass с вариантами ответов
     *
     * @return object
     */
    public function getAnswerOptions()
    {
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'string', 'max' => 2000],
            [['comment'], 'trim'],
            [['checkbox', 'comment'], 'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'checkbox' => 'Выберите один из вариантов ответа',
            'comment' => 'Напишите комментарий'
        ];
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
        $this->_expertise->setEstimation($this->checkbox[0]);
        $this->_expertise->setComment($this->comment);
        $completed ? $this->_expertise->setCompleted() : false;
        return $this->_expertise->saveRecord();
    }

}