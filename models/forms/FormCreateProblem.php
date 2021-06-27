<?php


namespace app\models\forms;

use app\models\ConfirmSegment;
use app\models\Problems;
use app\models\Segments;
use yii\base\ErrorException;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class FormCreateProblem extends Model
{

    public $description;
    public $action_to_check;
    public $result_metric;
    public $basic_confirm_id;
    public $_cacheManager;
    public $cachePath;


    /**
     * FormCreateProblem constructor.
     * @param Segments $preliminaryHypothesis
     * @param array $config
     */
    public function __construct(Segments $preliminaryHypothesis, $config = [])
    {
        $this->_cacheManager = new CacheForm();
        $this->cachePath = self::getCachePath($preliminaryHypothesis);
        $cacheName = 'formCreateHypothesisCache';
        if ($cache = $this->_cacheManager->getCache($this->cachePath, $cacheName)) {
            $className = explode('\\', self::class)[3];
            foreach ($cache[$className] as $key => $value) $this[$key] = $value;
        }

        parent::__construct($config);
    }


    /**
     * Получить путь к кэшу формы
     * @param Segments $preliminaryHypothesis
     * @return string
     */
    public static function getCachePath(Segments $preliminaryHypothesis)
    {
        $project = $preliminaryHypothesis->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$preliminaryHypothesis->id.'/problems/formCreate/';
        return $cachePath;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'action_to_check', 'result_metric'], 'trim'],
            [['description', 'action_to_check', 'result_metric'], 'string', 'max' => 2000],
            [['basic_confirm_id'], 'integer'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'description' => 'Описание гипотезы проблемы сегмента',
            'action_to_check' => 'Действие для проверки',
            'result_metric' => 'Метрика результата',
        ];
    }


    /**
     * @return Problems
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create()
    {
        $last_model = Problems::find()->where(['basic_confirm_id' => $this->basic_confirm_id])->orderBy(['id' => SORT_DESC])->one();
        $confirmSegment = ConfirmSegment::findOne($this->basic_confirm_id);

        $problem = new Problems();
        $problem->project_id = $confirmSegment->hypothesis->projectId;
        $problem->segment_id = $confirmSegment->segmentId;
        $problem->basic_confirm_id = $this->basic_confirm_id;
        $problem->description = $this->description;
        $problem->action_to_check = $this->action_to_check;
        $problem->result_metric = $this->result_metric;
        $last_model_number = explode(' ',$last_model->title)[1];
        $problem->title = 'ГПС ' . ($last_model_number + 1);

        if ($problem->save()) {
            $this->_cacheManager->deleteCache($this->cachePath); // Удаление кэша формы создания
            return $problem;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить новую проблему');
    }

}