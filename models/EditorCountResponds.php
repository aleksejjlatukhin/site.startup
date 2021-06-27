<?php


namespace app\models;

use app\models\forms\CreateRespondProblemForm;
use app\models\forms\CreateRespondSegmentForm;
use app\models\forms\CreateRespondGcpForm;
use app\models\forms\CreateRespondMvpForm;
use app\models\interfaces\ConfirmationInterface;
use yii\base\Model;

class EditorCountResponds extends Model
{

    /**
     * @param ConfirmationInterface $confirm
     */
    public function edit(ConfirmationInterface $confirm)
    {

        $responds = $confirm->responds;
        $countResponds = count($responds);

        if (($countResponds) < $confirm->count_respond){
            for ($count = $countResponds; $count < $confirm->count_respond; $count++ )
            {
                $newRespond[$count] = self::getCreateForm($confirm);
                $newRespond[$count]->setConfirmId($confirm->id);
                $newRespond[$count]->setName('Респондент ' . ($count+1));
                $newRespond[$count]->create();
            }
        }else{
            $minus = $countResponds - $confirm->count_respond;
            $responds = array_reverse($responds);
            foreach ($responds as $i => $respond) {
                if ($i < $minus) $respond->delete();
            }
        }

    }


    /**
     * @param ConfirmationInterface $confirm
     * @return CreateRespondProblemForm|CreateRespondSegmentForm|CreateRespondGcpForm|CreateRespondMvpForm|bool
     */
    private static function getCreateForm(ConfirmationInterface $confirm)
    {
        if ($confirm->stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return new CreateRespondSegmentForm($confirm);
        } elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return new CreateRespondProblemForm($confirm);
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return new CreateRespondGcpForm($confirm);
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return new CreateRespondMvpForm($confirm);
        }
        return false;
    }

}