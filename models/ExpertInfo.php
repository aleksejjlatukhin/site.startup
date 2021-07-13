<?php


namespace app\models;

use yii\db\ActiveRecord;

class ExpertInfo extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_info';
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['education', 'academic_degree', 'position', 'scope_professional_competence',
                'publications', 'implemented_projects', 'role_in_implemented_projects'], 'required'],
            [['education', 'academic_degree', 'position', 'scope_professional_competence',
                'publications', 'implemented_projects', 'role_in_implemented_projects'], 'trim'],
            [['education', 'academic_degree', 'position'], 'string', 'max' => 255],
            [['scope_professional_competence', 'publications', 'implemented_projects', 'role_in_implemented_projects'], 'string', 'max' => 2000],
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'education' => 'Образование',
            'academic_degree' => 'Ученая степень',
            'position' => 'Должность',
            'scope_professional_competence' => 'Сфера профессиональной компетенции',
            'publications' => 'Научные публикации',
            'implemented_projects' => 'Реализованные проекты',
            'role_in_implemented_projects' => 'Роль в реализованных проектах'
        ];
    }
}