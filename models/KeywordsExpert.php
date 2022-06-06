<?php


namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс, содержит ключевые слова по поиску экспертов
 *
 * Class KeywordsExpert
 * @package app\models
 *
 * @property int $id                        Идентификатор записи
 * @property int $expert_id                 Идентификатор эксперта в таб.User
 * @property int $description               Ключевые слова
 */
class KeywordsExpert extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'keywords_expert';
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['expert_id', 'description'], 'required'],
            [['expert_id'], 'integer'],
            ['description', 'trim'],
            ['description', 'string', 'max' => 2000],
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'description' => 'Ключевые слова'
        ];
    }


    /**
     * @param int $expertId
     */
    public function setExpertId($expertId)
    {
        $this->expert_id = $expertId;
    }


    /**
     * @return int
     */
    public function getExpertId()
    {
        return $this->expert_id;
    }


    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Сохранение ключевых слов эксперта при регистрации
     * @param int $expertId
     * @param string $description
     */
    public static function create($expertId, $description)
    {
        $keywords = new self();
        $keywords->setExpertId($expertId);
        $keywords->setDescription($description);
        $keywords->save();
    }


    /**
     * Редактирование ключевых слов эксперта
     * @param $description
     */
    public function edit($description)
    {
        $this->setDescription($description);
        $this->save();
    }
}