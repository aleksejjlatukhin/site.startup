<?php


namespace app\controllers;


use yii\web\Controller;
use Yii;

class AppController extends Controller
{

    public function delTree($dir)
    {
        if ($objs = glob($dir."/*"))
        {
            foreach($objs as $obj)
            {
                is_dir($obj) ? $this->delTree($obj) : unlink($obj);
            }
        }
        rmdir($dir);

    }

}