<?php


namespace app\models\forms;

use app\models\ConfirmGcp;
use app\models\Gcps;
use app\models\Problems;
use app\models\Mvps;
use app\models\Projects;
use app\models\Segments;
use yii\base\ErrorException;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class FormCreateMvp extends Model
{

    public $description;
    public $basic_confirm_id;
    public $_cacheManager;
    public $cachePath;


    /**
     * FormCreateMvp constructor.
     * @param Gcps $preliminaryHypothesis
     * @param array $config
     */
    public function __construct(Gcps $preliminaryHypothesis, $config = [])
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
     * @param Gcps $preliminaryHypothesis
     * @return string
     */
    public static function getCachePath(Gcps $preliminaryHypothesis)
    {
        $problem = $preliminaryHypothesis->problem;
        $segment = $preliminaryHypothesis->segment;
        $project = $preliminaryHypothesis->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.
            '/problems/problem-'.$problem->id.'/gcps/gcp-'.$preliminaryHypothesis->id.'/mvps/formCreate/';

        return $cachePath;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'trim'],
            [['description'], 'string', 'max' => 2000],
            [['basic_confirm_id'], 'integer'],
        ];
    }


    /**
     * @return Mvps
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create()
    {
        $last_model = Mvps::find()->where(['basic_confirm_id' => $this->basic_confirm_id])->orderBy(['id' => SORT_DESC])->one();
        $confirmGcp = ConfirmGcp::findOne($this->basic_confirm_id);
        $gcp = Gcps::findOne($confirmGcp->gcpId);
        $problem = Problems::findOne($gcp->problemId);
        $segment = Segments::findOne($gcp->segmentId);
        $project = Projects::findOne($gcp->projectId);

        $mvp = new Mvps();
        $mvp->project_id = $project->id;
        $mvp->segment_id = $segment->id;
        $mvp->problem_id = $problem->id;
        $mvp->gcp_id = $gcp->id;
        $mvp->basic_confirm_id = $this->basic_confirm_id;
        $mvp->description = $this->description;
        $last_model_number = explode(' ',$last_model->title)[1];
        $mvp->title = 'MVP ' . ($last_model_number + 1);

        if ($mvp->save()){
            $this->_cacheManager->deleteCache($this->cachePath); // Удаление кэша формы создания
            return $mvp;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить новый продукт (MVP)');
    }

}