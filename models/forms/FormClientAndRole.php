<?php


namespace app\models\forms;

use yii\base\Model;

/**
 * Форма указания организации и роли при регистрации
 *
 * Class FormClientAndRole
 * @package app\models\forms
 *
 * @property $clientId
 * @property $role
 */
class FormClientAndRole extends Model
{

    public $clientId;
    public $role;
}