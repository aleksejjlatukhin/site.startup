<?php


namespace app\models\forms;

use yii\base\Model;
use app\models\User;
use app\models\Projects;
use app\models\Segment;
use app\models\Interview;
use app\models\Respond;
use app\models\DescInterview;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class FormUpdateConfirmSegment extends Model
{
    public $id;
    public $segment_id;
    public $count_respond;
    public $count_positive;
    public $greeting_interview;
    public $view_interview;
    public $reason_interview;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['segment_id'], 'integer'],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'trim'],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'string', 'max' => '2000'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Количество респондентов, соответствующих сегменту',
            'greeting_interview' => 'Приветствие в начале встречи',
            'view_interview' => 'Информация о вас для респондентов',
            'reason_interview' => 'Причина и тема (что побудило) для проведения исследования',
        ];
    }

    /**
     * FormUpdateConfirmSegment constructor.
     * @param $id
     * @param array $config
     */
    public function __construct($id, $config = [])
    {
        $confirm_segment = Interview::findOne($id);

        $this->id = $id;
        $this->segment_id = $confirm_segment->segment_id;
        $this->count_respond = $confirm_segment->count_respond;
        $this->count_positive = $confirm_segment->count_positive;
        $this->greeting_interview = $confirm_segment->greeting_interview;
        $this->view_interview = $confirm_segment->view_interview;
        $this->reason_interview = $confirm_segment->reason_interview;

        parent::__construct($config);
    }

    /**
     * @return Interview|bool|null
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     * @throws \yii\db\StaleObjectException
     */
    public function update()
    {
        if ($this->validate()) {

            $confirm_segment = Interview::findOne($this->id);
            $confirm_segment->count_respond = $this->count_respond;
            $confirm_segment->count_positive = $this->count_positive;
            $confirm_segment->greeting_interview = $this->greeting_interview;
            $confirm_segment->view_interview = $this->view_interview;
            $confirm_segment->reason_interview = $this->reason_interview;

            if ($confirm_segment->save()) {
                $this->updateCountResponds();
                return $confirm_segment;
            }
            throw new NotFoundHttpException('Ошибка. Неудалось сохранить изменения');
        }
        return false;
    }


    /**
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     * @throws \yii\db\StaleObjectException
     */
    private function updateCountResponds ()
    {
        $segment = Segment::findOne($this->segment_id);
        $project = Projects::findOne($segment->project_id);
        $user = User::findOne($project->user_id);
        $responds = Respond::find()->where(['interview_id' => $this->id])->all();

        if ((count($responds)+1) <= $this->count_respond){
            for ($count = count($responds) + 1; $count <= $this->count_respond; $count++ )
            {
                $newRespond[$count] = new Respond();
                $newRespond[$count]->interview_id = $this->id;
                $newRespond[$count]->name = 'Респондент ' . $count;
                $newRespond[$count]->save();
            }
        }else{
            $minus = count($responds) - $this->count_respond;
            $respond = Respond::find()->where(['interview_id' => $this->id])->orderBy(['id' => SORT_DESC])->limit($minus)->all();
            foreach ($respond as $item)
            {
                // Удаление интервью респондента
                $descInterview = DescInterview::find()->where(['respond_id' => $item->id])->one();
                if ($descInterview) $descInterview->delete();
                // Удаление директории респондента
                $del_dir = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/segments/segment-'.$segment->id.'/interviews/respond-'.$item->id;
                if (file_exists($del_dir)) FileHelper::removeDirectory($del_dir);
                // Удаление кэша для форм респондента
                $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/confirm/interviews/respond-'.$item->id;
                if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);
                // Удаление респондента
                $item->delete();
            }
        }
    }

}