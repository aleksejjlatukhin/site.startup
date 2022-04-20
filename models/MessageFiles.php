<?php


namespace app\models;

use yii\db\ActiveRecord;

class MessageFiles extends ActiveRecord
{

    const CATEGORY_ADMIN = 1;
    const CATEGORY_MAIN_ADMIN = 2;
    const CATEGORY_TECHNICAL_SUPPORT = 3;
    const CATEGORY_EXPERT = 4;
    const CATEGORY_MANAGER = 5;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_files';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category', 'message_id'], 'integer'],
            [['file_name', 'server_file'], 'string', 'max' => 255],
        ];
    }
}