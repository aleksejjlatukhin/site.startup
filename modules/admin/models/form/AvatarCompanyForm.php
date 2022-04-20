<?php


namespace app\modules\admin\models\form;


use app\models\ClientSettings;
use yii\base\Exception;
use yii\base\Model;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;
use Yii;


/**
 * Форма загрузки и изменения аватра организации
 *
 * Class AvatarCompanyForm
 * @package app\modules\admin\models\form
 *
 * @property int $clientId
 * @property $loadImage
 * @property string $imageMax
 */
class AvatarCompanyForm extends Model
{

    public $clientId;
    public $loadImage;
    public $imageMax;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clientId'], 'integer'],
            [['imageMax'], 'string', 'max' => 255],
            [['loadImage'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg'],
        ];
    }


    /**
     * AvatarCompanyForm constructor.
     * @param int $clientId
     * @param array $config
     */
    public function __construct($clientId, $config = [])
    {
        $this->setClientId($clientId);
        parent::__construct($config);
    }


    /**
     * @return bool
     * @throws Exception
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function loadMinImage()
    {
        $clientSettings = ClientSettings::findOne(['client_id' => $this->clientId]);

        if ($_POST['imageMin']) {

            $path = UPLOAD . 'company-' . $this->clientId . '/avatar/';
            if (!is_dir($path)) FileHelper::createDirectory($path);

            $str = Yii::$app->security->generateRandomString(8);
            $file = 'avatar_' . $str . '_min.png';
            $uploadFile = $path . $file;

            $img = str_replace('data:image/png;base64,', '', $_POST['imageMin']);
            $img = str_replace(' ', '+', $img);
            $fileData = base64_decode($img);

            $url = $uploadFile;
            file_put_contents($url, $fileData);

            // Обновление аватарки
            if ($_POST['imageMax']) {

                if ($this->deleteOldAvatarImages()) {

                    $clientSettings->setAvatarMaxImage($_POST['imageMax']);
                    $clientSettings->setAvatarImage($file);
                    $clientSettings->update();

                    return true;
                }
            } else {
                // Редактирование аватарки
                unlink($path . $clientSettings->getAvatarImage());
                $clientSettings->setAvatarImage($file);
                return $clientSettings->update() ? true : false;
            }
        }
        return false;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function loadMaxImage()
    {
        $path = UPLOAD . 'company-' . $this->clientId . '/avatar/';
        if (!is_dir($path)) FileHelper::createDirectory($path);

        $uploadFile = $path . $_FILES['file']['name'];
        $arr = array();

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            $arr['success'] = true;
            $arr['path_max'] = '/web/'.$uploadFile;
            $arr['imageMax'] = $_FILES['file']['name'];
        } else {
            $arr['error'] = true;
        }

        return $arr;
    }

    /**
     * @return bool
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function deleteOldAvatarImages ()
    {
        $clientSettings = ClientSettings::findOne(['client_id' => $this->clientId]);
        $path = UPLOAD . 'company-' . $this->clientId . '/avatar/';

        if (is_file($path . $clientSettings->getAvatarMaxImage())) unlink($path . $clientSettings->getAvatarMaxImage());
        if (is_file($path . $clientSettings->getAvatarImage())) unlink($path . $clientSettings->getAvatarImage());

        $clientSettings->setAvatarMaxImage(null);
        $clientSettings->setAvatarImage(null);
        $clientSettings->update();

        return true;
    }


    /**
     * @return bool
     */
    public function deleteUnusedImage ()
    {
        if ($_POST['imageMax']) {
            $path = UPLOAD . 'company-' . $this->clientId . '/avatar/';
            unlink($path . $_POST['imageMax']);
            return true;
        }
        return false;
    }


    /**
     * @return int
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getLoadImage()
    {
        return $this->loadImage;
    }

    /**
     * @param mixed $loadImage
     */
    public function setLoadImage($loadImage)
    {
        $this->loadImage = $loadImage;
    }

    /**
     * @return string
     */
    public function getImageMax()
    {
        return $this->imageMax;
    }

    /**
     * @param string $imageMax
     */
    public function setImageMax($imageMax)
    {
        $this->imageMax = $imageMax;
    }
}