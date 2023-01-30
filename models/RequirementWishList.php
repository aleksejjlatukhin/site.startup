<?php

namespace app\models;

use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/**
 * Класс хранит информацию в бд о запросах компаний B2B сегмента
 *
 * Class RequirementWishList
 * @package app\models
 *
 * @property int $id                                        идентификатор записи
 * @property int $wish_list_id                              идентификатор списка запросов компаний B2B сегмента
 * @property string $requirement                            Описание запроса
 *
 * @property WishList $wishList                             Список запросов компаний B2B сегмента
 * @property ReasonRequirementWishList[] $reasons           Причины запроса
 */
class RequirementWishList extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'requirement_wish_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['wish_list_id', 'requirement'], 'required'],
            [['requirement'], 'string', 'max' => 2000],
            [['requirement'], 'trim'],
            [['wish_list_id'], 'integer'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'requirement' => 'Описание запроса',
        ];
    }

    /**
     * Получить список пожеланий компаний B2B сегмента
     *
     * @return ActiveQuery
     */
    public function getWishList(): ActiveQuery
    {
        return $this->hasOne(WishList::class, ['id' => 'wish_list_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getReasons(): ActiveQuery
    {
        return $this->hasMany(ReasonRequirementWishList::class, ['requirement_wish_list_id' => 'id']);
    }

    /**
     * @param int $id
     * @return bool|string
     */
    public function create(int $id)
    {
        try {
            $model = new self();
            $model->setWishListId($id);
            $model->setRequirement($_POST['RequirementWishList']['requirement']);
            if ($model->save()) {
                foreach ($_POST['RequirementWishList']['reasons'] as $reason) {
                    $newReason = new ReasonRequirementWishList();
                    $newReason->setRequirementWishListId($model->getId());
                    $newReason->setReason($reason['reason']);
                    $newReason->save();
                }
            }
            return true;
        }catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @return bool|string
     */
    public function updateRecord()
    {
        try {

            $this->setRequirement($_POST['RequirementWishList']['requirement']);

            if ($this->save()) {
                $query = array_values($_POST['RequirementWishList']['reasons']);
                $reasons = $this->reasons;

                if (count($query) > count($reasons)) {

                    foreach ($query as $i => $q) {

                        if (($i+1) <= count($reasons)) {
                            $reasons[$i]->setReason($q['reason']);
                        } else {
                            $reasons[$i] = new ReasonRequirementWishList();
                            $reasons[$i]->setReason($q['reason']);
                            $reasons[$i]->setRequirementWishListId($this->getId());
                        }
                        $reasons[$i]->save();
                    }

                } else {

                    foreach ($query as $i => $q) {
                        $reasons[$i]->setReason($q['reason']);
                        $reasons[$i]->setRequirementWishListId($this->getId());
                        $reasons[$i]->save();
                    }
                }
                return true;
            }
            return 'Error';
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @return bool|string
     * @throws Throwable
     */
    public function deleteRecord()
    {
        try {
            $reasons = $this->reasons;
            if ($reasons) {
                foreach ($reasons as $reason) {
                    $reason->delete();
                }
            }
            $this->delete();
            return true;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getWishListId(): int
    {
        return $this->wish_list_id;
    }

    /**
     * @param int $wish_list_id
     */
    public function setWishListId(int $wish_list_id): void
    {
        $this->wish_list_id = $wish_list_id;
    }

    /**
     * @return string
     */
    public function getRequirement(): string
    {
        return $this->requirement;
    }

    /**
     * @param string $requirement
     */
    public function setRequirement(string $requirement): void
    {
        $this->requirement = $requirement;
    }
}