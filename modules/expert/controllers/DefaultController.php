<?php

namespace app\modules\expert\controllers;

use yii\web\Controller;

/**
 * Default controller for the `expert` module
 */
class DefaultController extends AppExpertController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
