<?php


namespace app\models\forms;

use app\models\ConfirmMvp;
use yii\web\NotFoundHttpException;

/**
 * Форма обновления подтверждения гипотезы mvp-продукта
 *
 * Class FormUpdateConfirmMvp
 * @package app\models\forms
 */
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
        $this->setEditorCountRespond();

        $this->setParams([
            'id' => $confirmId,
            'count_respond' => $confirm->getCountRespond(),
            'count_positive' => $confirm->getCountPositive(),
        ]);

        parent::__construct($config);
    }


    /**
     * @param array $params
     * @return mixed|void
     */
    protected function setParams(array $params)
    {
        $this->setId($params['id']);
        $this->setCountRespond($params['count_respond']);
        $this->setCountPositive($params['count_positive']);
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
     * @return ConfirmMvp|bool|mixed|null
     * @throws NotFoundHttpException
     */
    public function update()
    {
        if ($this->validate()) {

            $confirm = ConfirmMvp::findOne($this->getId());
            $confirm->setCountRespond($this->getCountRespond());
            $confirm->setCountPositive($this->getCountPositive());

            if ($confirm->save()) {
                $this->getEditorCountRespond()->edit($confirm);
                return $confirm;
            }
            throw new NotFoundHttpException('Ошибка. Неудалось сохранить изменения');
        }
        return false;
    }
}