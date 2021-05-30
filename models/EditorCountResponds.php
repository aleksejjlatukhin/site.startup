<?php


namespace app\models;

use app\models\forms\CreateRespondConfirmForm;
use app\models\forms\CreateRespondForm;
use app\models\forms\CreateRespondGcpForm;
use app\models\forms\CreateRespondMvpForm;
use app\models\interfaces\ConfirmationInterface;
use Throwable;
use yii\base\Model;
use yii\db\StaleObjectException;

class EditorCountResponds extends Model
{


    /**
     * @param ConfirmationInterface $confirm
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function edit(ConfirmationInterface $confirm)
    {
        switch (get_class($confirm)) {

            case 'app\models\Interview':

                $countResponds = Respond::find()->where(['interview_id' => $confirm->id])->count();

                if (($countResponds) < $confirm->count_respond){
                    for ($count = $countResponds; $count < $confirm->count_respond; $count++ )
                    {
                        $newRespond[$count] = new CreateRespondForm($confirm);
                        $newRespond[$count]->setConfirmId($confirm->id);
                        $newRespond[$count]->setName('Респондент ' . ($count+1));
                        $newRespond[$count]->create();
                    }
                }else{
                    $minus = $countResponds - $confirm->count_respond;
                    $responds = Respond::find()->where(['interview_id' => $confirm->id])->orderBy(['id' => SORT_DESC])->limit($minus)->all();
                    foreach ($responds as $respond) $respond->delete();
                }

                break;

            case 'app\models\ConfirmProblem':

                $countResponds = RespondsConfirm::find()->where(['confirm_problem_id' => $confirm->id])->count();

                if (($countResponds) < $confirm->count_respond){
                    for ($count = $countResponds; $count < $confirm->count_respond; $count++ )
                    {
                        $newRespond[$count] = new CreateRespondConfirmForm($confirm);
                        $newRespond[$count]->setConfirmId($confirm->id);
                        $newRespond[$count]->setName('Респондент ' . ($count+1));
                        $newRespond[$count]->create();
                    }
                } else {
                    $minus = $countResponds - $confirm->count_respond;
                    $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $confirm->id])->orderBy(['id' => SORT_DESC])->limit($minus)->all();
                    foreach ($responds as $respond) $respond->delete();
                }

                break;

            case 'app\models\ConfirmGcp':

                $countResponds = RespondsGcp::find()->where(['confirm_gcp_id' => $confirm->id])->count();

                if (($countResponds) < $confirm->count_respond){
                    for ($count = $countResponds; $count < $confirm->count_respond; $count++ )
                    {
                        $newRespond[$count] = new CreateRespondGcpForm($confirm);
                        $newRespond[$count]->setConfirmId($confirm->id);
                        $newRespond[$count]->setName('Респондент ' . ($count+1));
                        $newRespond[$count]->create();
                    }
                } else {
                    $minus = $countResponds - $confirm->count_respond;
                    $responds = RespondsGcp::find()->where(['confirm_gcp_id' => $confirm->id])->orderBy(['id' => SORT_DESC])->limit($minus)->all();
                    foreach ($responds as $respond) $respond->delete();
                }

                break;

            case 'app\models\ConfirmMvp':

                $countResponds = RespondsMvp::find()->where(['confirm_mvp_id' => $confirm->id])->count();

                if (($countResponds) < $confirm->count_respond){
                    for ($count = $countResponds; $count < $confirm->count_respond; $count++ )
                    {
                        $newRespond[$count] = new CreateRespondMvpForm($confirm);
                        $newRespond[$count]->setConfirmId($confirm->id);
                        $newRespond[$count]->setName('Респондент ' . ($count+1));
                        $newRespond[$count]->create();
                    }
                } else {
                    $minus = $countResponds - $confirm->count_respond;
                    $responds = RespondsMvp::find()->where(['confirm_mvp_id' => $confirm->id])->orderBy(['id' => SORT_DESC])->limit($minus)->all();
                    foreach ($responds as $respond) $respond->delete();
                }

                break;
        }
    }
}