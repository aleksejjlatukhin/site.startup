<?php


namespace app\models;


use yii\db\ActiveRecord;

class TypeOfActivityB2B extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'type_of_activity_b2b';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'kind_of_interaction', 'title'], 'required'],
            ['parent_id', 'integer'],
            ['kind_of_interaction', 'default', 'value' => 10],
            ['name', 'trim'],
            ['name', 'string', 'max' => 255],
        ];
    }

    public static function getListOfAreasOfActivity()
    {
        $listOfAreasOfActivity = self::find()->select(['id', 'name'])->orderBy(['name' => SORT_ASC])->where(['parent_id' => 0])->asArray()->all();

        return $listOfAreasOfActivity;
    }

    public static function getListOfActivities($areaOfActivity_id)
    {
        $ListOfActivities = self::find()->select(['id', 'name'])->orderBy(['name' => SORT_ASC])->where(['parent_id' => $areaOfActivity_id])->asArray()->all();

        return $ListOfActivities;
    }
}