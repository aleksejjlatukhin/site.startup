<?php


namespace app\models\forms;

use app\models\Interview;
use app\models\Projects;
use app\models\Respond;
use app\models\Segment;
use app\models\User;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class CreateRespondForm extends Model
{
    public $name;
    public $interview_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'uniqueName'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Фамилия, имя, отчество',
        ];
    }


    /**
     * @return Respond
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function create ()
    {
        $interview = Interview::findOne($this->interview_id);
        $segment = Segment::findOne($interview->segment_id);
        $project = Projects::findOne($segment->project_id);
        $user = User::findOne($project->user_id);

        $model = new Respond();
        $model->interview_id = $this->interview_id;
        $model->name = $this->name;

        if ($model->save()) {
            // Добавление пустых ответов на вопросы для нового респондента
            $model->addAnswersForNewRespond();
            // Обновление данных подтверждения
            $interview->count_respond = $interview->count_respond + 1;
            $interview->save();
            // Удаление кэша формы создания
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/confirm/formCreateRespond';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Неудалось добавить нового респондента');
    }

    /**
     * @param $attr
     */
    public function uniqueName($attr)
    {
        $models = Respond::findAll(['interview_id' => $this->interview_id]);

        foreach ($models as $item){

            if (mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }
}