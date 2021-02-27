<?php


namespace app\models\forms;

use app\models\User;
use yii\base\Model;
use yii\helpers\FileHelper;

class AvatarForm extends Model
{
    public $userId;
    public $loadImage;
    public $imageMax;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId'], 'integer'],
            [['imageMax'], 'string', 'max' => 255],
            [['loadImage'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg'],
        ];
    }


    /**
     * AvatarForm constructor.
     * @param $userId
     * @param array $config
     */
    public function __construct($userId, $config = [])
    {
        $user = User::findOne($userId);
        $this->userId = $user->id;
        parent::__construct($config);
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function loadMinImage()
    {
        $user = User::findOne($this->userId);

        if ($_POST['imageMin']) {

            $path = UPLOAD . 'user-' . $this->userId . '/avatar/';
            if (!is_dir($path)) FileHelper::createDirectory($path);

            $str = \Yii::$app->security->generateRandomString(8);
            $file = 'avatar_' . $str . '_min.png';
            $uploadfile = $path . $file;

            $img = str_replace('data:image/png;base64,', '', $_POST['imageMin']);
            $img = str_replace(' ', '+', $img);
            $fileData = base64_decode($img);

            $url = $uploadfile;
            file_put_contents($url, $fileData);

            // Обновление аватарки
            if ($_POST['imageMax']) {

                if ($this->deleteOldAvatarImages()) {

                    $user->avatar_max_image = $_POST['imageMax'];
                    $user->avatar_image = $file;
                    $user->update();

                    return true;
                }
            } else {
                // Редактирование аватарки
                unlink($path . $user->avatar_image);
                $user->avatar_image = $file;
                return $user->update() ? true : false;
            }
        }
        return false;
    }

    /**
     * @return array
     * @throws \yii\base\Exception
     */
    public function loadMaxImage()
    {
        $path = UPLOAD.'user-'.$this->userId.'/avatar/';
        if (!is_dir($path)) FileHelper::createDirectory($path);

        $uploadfile = $path . $_FILES['file']['name'];
        $arr = array();

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            $arr['success'] = true;
            $arr['path_max'] = '/web/'.$uploadfile;
            $arr['imageMax'] = $_FILES['file']['name'];
        } else {
            $arr['error'] = true;
        }

        return $arr;
    }

    /**
     * @return bool
     */
    public function deleteOldAvatarImages ()
    {
        $user = User::findOne($this->userId);
        $path = UPLOAD . 'user-' . $user->id . '/avatar/';

        if (is_file($path . $user->avatar_max_image)) unlink($path . $user->avatar_max_image);
        if (is_file($path . $user->avatar_image)) unlink($path . $user->avatar_image);

        $user->avatar_max_image = null;
        $user->avatar_image = null;
        $user->save();

        return true;
    }


    public function deleteUnusedImage ()
    {
        if ($_POST['imageMax']) {

            $user = User::findOne($this->userId);
            $path = UPLOAD . 'user-' . $user->id . '/avatar/';
            unlink($path . $_POST['imageMax']);
            return true;
        }
        return false;
    }

}