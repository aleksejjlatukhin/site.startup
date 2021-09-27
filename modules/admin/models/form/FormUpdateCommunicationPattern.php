<?php


namespace app\modules\admin\models\form;

use app\models\CommunicationPatterns;
use Throwable;
use yii\base\Model;
use Yii;
use yii\db\StaleObjectException;

/**
 * Форма редактирования шаблона коммуникации
 * Class FormUpdateCommunicationPattern
 * @package app\modules\admin\models\form
 */
class FormUpdateCommunicationPattern extends Model
{

    public $id;
    public $communication_type;
    public $description;
    public $project_access_period;


    /**
     * FormUpdateCommunicationPattern constructor.
     * @param $id
     * @param $communicationType
     * @param array $config
     */
    public function __construct($id, $communicationType, $config = [])
    {
        $pattern = CommunicationPatterns::find()
            ->where(['id' => $id, 'communication_type' => $communicationType])
            ->andWhere(['initiator' => Yii::$app->user->id, 'is_remote' => CommunicationPatterns::NOT_REMOTE])
            ->one();

        $this->setParams($pattern);
        parent::__construct($config);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['communication_type', 'project_access_period'], 'integer'],
            [['description', 'communication_type'], 'required'],
            [['description'], 'string', 'max' => 255],
            [['description'], 'trim'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'description' => 'Описание шаблона коммуникации',
            'project_access_period' => 'Срок доступа к проекту'
        ];
    }


    /**
     * Установка параметров формы
     * @param $pattern
     */
    public function setParams($pattern)
    {
        $this->id = $pattern->id;
        $this->communication_type = $pattern->communication_type;
        $this->description = $pattern->description;
        $this->project_access_period = $pattern->project_access_period;
    }


    /**
     * Обновление шаблона
     * коммуникации
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function update()
    {
        $pattern = CommunicationPatterns::findOne($this->id);
        $pattern->description = $this->description;
        $pattern->project_access_period = $this->project_access_period;
        $pattern->update(true, ['description', 'project_access_period']);
    }
}