<?php


namespace app\modules\expert\controllers;


use app\models\CommunicationResponse;
use app\models\CommunicationTypes;
use app\models\ProjectCommunications;
use app\models\Projects;
use app\models\User;
use app\modules\expert\models\form\FormCreateCommunicationResponse;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class CommunicationsController extends AppExpertController
{

    public $layout = '@app/modules/expert/views/layouts/main';


    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function beforeAction($action)
    {

        if ($action->id == 'notifications') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])
                || (User::isUserExpert(Yii::$app->user->identity['username']) && (Yii::$app->user->getId() == Yii::$app->request->get('id')))) {

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } else{
            return parent::beforeAction($action);
        }

    }


    /**
     * Уведомления (коммуникации)
     * эксперта по проектам
     *
     * @param int $id
     * @return string
     */
    public function actionNotifications($id)
    {
        $user = User::findOne($id);
        // Проекты, по которым у эксперта есть коммуникации
        $projects = Projects::find()
            ->distinct()
            ->leftJoin('project_communications', '`project_communications`.`project_id` = `projects`.`id`')
            ->where(['or', ['project_communications.sender_id' => $id], ['project_communications.adressee_id' => $id]])
            ->orderBy('project_communications.id DESC')
            ->all();

        return $this->render('notifications', [
            'projects' => $projects,
            'user' => $user,
        ]);
    }


    /**
     * Получить уведомления
     * (коммуникации) по проекту
     *
     * @param int $project_id
     * @return array|bool
     */
    public function actionGetCommunications($project_id)
    {
        if(Yii::$app->request->isAjax) {

            $response = $this->responseForGetCommunications($project_id);
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param int $project_id
     * @return array
     */
    private function responseForGetCommunications($project_id)
    {
        $communications = ProjectCommunications::find()
            ->where(['project_id' => $project_id])
            ->andWhere(['not', ['type' => CommunicationTypes::EXPERT_ANSWERS_QUESTION_ABOUT_READINESS_CONDUCT_EXPERTISE]])
            ->andWhere(['or', ['adressee_id' => Yii::$app->user->id], ['sender_id' => Yii::$app->user->id]])
            ->orderBy('id DESC')
            ->all();

        return $response = [
            'renderAjax' => $this->renderAjax('ajax_get_communications', [
            'communications' => $communications])
        ];
    }


    /**
     * Прочтение уведомления
     * (коммуникации) по проекту
     * экспертом
     *
     * @param int $id
     * @return array|bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionReadCommunication($id)
    {
        if(Yii::$app->request->isAjax) {

            $response = $this->responseForReadCommunication($id);
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }

        return false;
    }


    /**
     * @param int $id
     * @return array
     * @throws StaleObjectException
     * @throws Throwable
     */
    private function responseForReadCommunication($id)
    {
        $communication = ProjectCommunications::findOne($id);
        $communication->setStatusRead();
        $communication->update();

        $user = User::findOne($communication->getAdresseeId());
        $countUnreadCommunications = $user->getCountUnreadCommunications();
        $countUnreadCommunicationsByProject = $user->getCountUnreadCommunicationsByProject($communication->getProjectId());

        return $response = [
            'project_id' => $communication->getProjectId(),
            'countUnreadCommunications' => $countUnreadCommunications,
            'countUnreadCommunicationsByProject' => $countUnreadCommunicationsByProject
        ];
    }


    /**
     * Получить форму для ответа
     * на уведомление (коммуникацию)
     *
     * @param int $id
     * @return bool|array
     */
    public function actionGetFormCommunicationResponse($id)
    {
        if(Yii::$app->request->isAjax) {

            $model = new FormCreateCommunicationResponse();
            $communication = ProjectCommunications::findOne($id);

            $response = ['renderAjax' => $this->renderAjax('ajax_get_form_communication_response', [
                'model' => $model, 'communication' => $communication])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * Метод для отправки
     * коммуникации
     *
     * @param int $adressee_id
     * @param int $project_id
     * @param int $type
     * @param int $triggered_communication_id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionSend($adressee_id, $project_id, $type, $triggered_communication_id)
    {
        if(Yii::$app->request->isAjax) {

            // Создаем новую коммуникацию
            $communication = new ProjectCommunications();
            $communication->setParams($adressee_id, $project_id, $type);
            $communication->setTriggeredCommunicationId($triggered_communication_id);
            if ($communication->save()) {
                // Создаем объект содержащий ответ по созданной коммуникации
                $communicationResponse = new FormCreateCommunicationResponse();
                $communicationResponse->setCommunicationId($communication->id);
                if ($communicationResponse->load(Yii::$app->request->post()) && $communicationResponse->create()) {

                    // Получаем коммуникацию, в ответ на которую была создана данная коммуникация
                    $communicationAnswered = $communication->getCommunicationAnswered();

                    // Если ответ эксперта отрицательный, то у коммуникации на которую была создана данная коммуникация
                    // меняем у объекта доступа к проекту параметр cancel,
                    // т.е. аннулируем доступ к проекту
                    if ($communicationResponse->answer == CommunicationResponse::NEGATIVE_RESPONSE) {
                        $communicationAnsweredAccessToProject = $communicationAnswered->userAccessToProject;
                        $communicationAnsweredAccessToProject->setCancel();
                        $communicationAnsweredAccessToProject->update();
                    }

                    // Делаем коммуникацию прочитанной (отвеченной)
                    $result_ReadCommunication = $this->responseForReadCommunication($communicationAnswered->id);

                    // Отправка письма гл.админу на почту
                    $this->sendCommunicationToEmail($communication);

                    // Получить обновленные коммуникации
                    $result_GetCommunications =  $this->actionGetCommunications($project_id);

                    $response = array_merge($result_ReadCommunication, $result_GetCommunications);
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * Отправка письма с уведомлением
     * на главного админа
     *
     * @param ProjectCommunications $communication
     * @return bool
     */
    public function sendCommunicationToEmail($communication)
    {
        /* @var $user User */
        $user = User::findOne($communication->getSenderId());
        /* @var $admin User */
        $admin = User::findOne($communication->getAdresseeId());

        if ($user) {
            return Yii::$app->mailer->compose('communications__FromExpertToMainAdmin', ['user' => $user, 'communication' => $communication])
                ->setFrom([Yii::$app->params['supportEmail'] => 'Spaccel.ru - Акселератор стартап-проектов'])
                ->setTo($admin->email)
                ->setSubject('Эксперт '.$user->second_name. ' '.$user->first_name.' отправил Вам новое уведомление на сайте Spaccel.ru')
                ->send();
        }

        return false;
    }




}