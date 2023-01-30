<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс хранит информацию в бд о локациях(городах) для списков запросов компаний B2B сегмента
 *
 * Class LocationWishList
 * @package app\models
 *
 * @property int $id                 идентификатор записи
 * @property string $name            наименование локации
 */
class LocationWishList extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'location_wish_list';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'max' => 255],
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Наименование локации'
        ];
    }

    /**
     * @return array
     */
    public static function getList(): array
    {
        $records = self::find()->all();
        $list = [];

        foreach ($records as $record) {
            /** @var self $record */
            $list[$record->getId()] = $record->getName();
        }
        return $list;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}