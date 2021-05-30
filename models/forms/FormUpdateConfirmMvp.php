<?php


namespace app\models\forms;

use app\models\ConfirmMvp;
use app\models\EditorCountResponds;
use Throwable;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class FormUpdateConfirmMvp extends FormUpdateConfirm
{


    /**
     * FormUpdateConfirmMvp constructor.
     * @param $confirmId
     * @param array $config
     */
    public function __construct($confirmId, $config = [])
    {
        $confirm = ConfirmMvp::findOne($confirmId);
        $this->_editorCountRespond = new EditorCountResponds();

        $this->setParams([
            'id' => $confirmId,
            'count_respond' => $confirm->count_respond,
            'count_positive' => $confirm->count_positive,
        ]);

        parent::__construct($config);
    }


    /**
     * @param array $params
     * @return mixed|void
     */
    protected function setParams(array $params)
    {
        $this->id = $params['id'];
        $this->count_respond = $params['count_respond'];
        $this->count_positive = $params['count_positive'];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'max' => '100'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Количество респондентов, соответствующих продукту (MVP)',
        ];
    }


    /**
     * @return ConfirmMvp|bool|null
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function update()
    {
        if ($this->validate()) {

            $confirm = ConfirmMvp::findOne($this->id);
            $confirm->setCountRespond($this->count_respond);
            $confirm->setCountPositive($this->count_positive);

            if ($confirm->save()) {
                $this->_editorCountRespond->edit($confirm);
                return $confirm;
            }
            throw new NotFoundHttpException('Ошибка. Неудалось сохранить изменения');
        }
        return false;
    }
}