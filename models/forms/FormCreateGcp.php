<?php


namespace app\models\forms;

use app\models\ConfirmProblem;
use yii\base\ErrorException;
use yii\base\Model;
use app\models\Projects;
use app\models\Segments;
use app\models\Problems;
use app\models\Gcps;
use yii\web\NotFoundHttpException;

class FormCreateGcp extends Model
{

    public $good;
    public $benefit;
    public $contrast;
    public $basic_confirm_id;
    public $_cacheManager;
    public $cachePath;


    /**
     * FormCreateGcp constructor.
     * @param Problems $preliminaryHypothesis
     * @param array $config
     */
    public function __construct(Problems $preliminaryHypothesis, $config = [])
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
     * @param Problems $preliminaryHypothesis
     * @return string
     */
    public static function getCachePath(Problems $preliminaryHypothesis)
    {
        $segment = $preliminaryHypothesis->segment;
        $project = $preliminaryHypothesis->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id. '/segments/segment-'.$segment->id
            .'/problems/problem-'.$preliminaryHypothesis->id.'/gcps/formCreate/';

        return $cachePath;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['good', 'benefit', 'contrast'], 'trim'],
            [['good', 'contrast'], 'string', 'max' => 255],
            [['benefit'], 'string', 'max' => 500],
            [['basic_confirm_id'], 'integer'],
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


    /**
     * @return Gcps
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create()
    {
        $last_model = Gcps::find()->where(['basic_confirm_id' => $this->basic_confirm_id])->orderBy(['id' => SORT_DESC])->one();
        $confirmProblem = ConfirmProblem::findOne($this->basic_confirm_id);
        $problem = Problems::findOne($confirmProblem->problemId);
        $segment = Segments::findOne($problem->segmentId);
        $project = Projects::findOne($problem->projectId);

        $gcp = new Gcps();
        $gcp->project_id = $project->id;
        $gcp->segment_id = $segment->id;
        $gcp->problem_id = $problem->id;
        $gcp->basic_confirm_id = $this->basic_confirm_id;
        $last_model_number = explode(' ',$last_model->title)[1];
        $gcp->title = 'ГЦП ' . ($last_model_number + 1);

        $gcp->description = 'Наш продукт ' . mb_strtolower($this->good) . ' ';
        $gcp->description .= 'помогает ' . mb_strtolower($segment->name) . ', ';
        $gcp->description .= 'который хочет удовлетворить проблему ' . mb_strtolower($problem->description) . ', ';
        $gcp->description .= 'избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, ' . mb_strtolower($this->benefit) . ', ';
        $gcp->description .= 'в отличии от ' . mb_strtolower($this->contrast) . '.';

        if ($gcp->save()){
            $this->_cacheManager->deleteCache($this->cachePath); // Удаление кэша формы создания
            return $gcp;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить новое ценностное предложение');
    }

}