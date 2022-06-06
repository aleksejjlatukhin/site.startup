<?php


namespace app\controllers;

use app\models\CheckingOnlineUser;
use app\models\User;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;

class AppController extends Controller
{

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) $this->setOnlineTime();
        return parent::beforeAction($action);
    }

    /**
     * @return bool
     */
    public function setOnlineTime()
    {
        $user = User::findOne(Yii::$app->user->id);
        /**
         * @var CheckingOnlineUser $checkingOnline
         */
        if ($checkingOnline = $user->checkingOnline) {
            $checkingOnline->setLastActiveTime();
            return true;
        } else {
            $checkingOnline = new CheckingOnlineUser();
            $checkingOnline->addCheckingOnline(Yii::$app->user->id);
            return true;
        }
    }
}