<?php

namespace app\controllers;

use app\models\forms\FormCreateConfirmProblem;
use app\models\forms\FormCreateGcp;
use app\models\forms\FormUpdateConfirmProblem;
use app\models\forms\FormUpdateQuestionConfirmProblem;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\QuestionsConfirmProblem;
use app\models\Respond;
use app\models\RespondsConfirm;
use app\models\Segment;
use app\models\User;
use kartik\mpdf\Pdf;
use Mpdf\MpdfException;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use Throwable;
use Yii;
use app\models\ConfirmProblem;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class ConfirmProblemController extends AppUserPartController
{

    /**
     * @param $action
     * @return bool
     * @throws HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['view']) || in_array($action->id, ['mpdf-questions-and-answers']) || in_array($action->id, ['mpdf-data-responds'])){

            $model = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::findOne(['id' => $model->gps_id]);
            $project = Projects::findOne(['id' => $problem->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::findOne(['id' => $model->gps_id]);
            $project = Projects::findOne(['id' => $problem->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $problem = GenerationProblem::findOne(Yii::$app->request->get());
            $interview = Interview::findOne(['id' => $problem->interview_id]);
            $segment = Segment::findOne(['id' => $interview->segment_id]);
            $project = Projects::findOne(['id' => $segment->project_id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['save-confirm-problem'])){

            $problem = GenerationProblem::findOne(Yii::$app->request->get());
            $interview = Interview::findOne(['id' => $problem->interview_id]);
            $segment = Segment::findOne(['id' => $interview->segment_id]);
            $project = Projects::findOne(['id' => $segment->project_id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif (in_array($action->id, ['add-questions'])){

            $model = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
            $project = Projects::find()->where(['id' => $problem->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif (in_array($action->id, ['delete-question'])){

            $question = QuestionsConfirmProblem::findOne(Yii::$app->request->get());
            $confirm_problem = ConfirmProblem::findOne(['id' => $question->confirm_problem_id]);
            $problem = GenerationProblem::findOne(['id' => $confirm_problem->gps_id]);
            $project = Projects::findOne(['id' => $problem->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $formUpdateConfirmProblem = new FormUpdateConfirmProblem($id);
        $problem = GenerationProblem::findOne($model->problemId);
        $interview = Interview::findOne($problem->confirmSegmentId);
        $segment = Segment::findOne($interview->segmentId);
        $project = Projects::findOne($segment->projectId);
        $questions = QuestionsConfirmProblem::findAll(['confirm_problem_id' => $id]);
        $newQuestion = new QuestionsConfirmProblem();

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('view', [
            'model' => $model,
            'formUpdateConfirmProblem' => $formUpdateConfirmProblem,
            'problem' => $problem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'questions' => $questions,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
        ]);
    }


    /**
     * @return bool|string
     */
    public function actionGetInstructionStepOne ()
    {
        if(Yii::$app->request->isAjax) {
            $response = $this->renderAjax('instruction_step_one');
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @return bool|string
     */
    public function actionGetInstructionStepTwo ()
    {
        if(Yii::$app->request->isAjax) {
            $response = $this->renderAjax('instruction_step_two');
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @return bool|string
     */
    public function actionGetInstructionStepThree ()
    {
        if(Yii::$app->request->isAjax) {
            $response = $this->renderAjax('instruction_step_three');
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * Проверка данных подтверждения на этапе разработки ГЦП
     * @param $id
     * @return array|bool
     */
    public function actionDataAvailabilityForNextStep($id)
    {
        $model = ConfirmProblem::findOne($id);
        $problem = GenerationProblem::findOne($model->problemId);
        $segment = Segment::findOne($problem->segmentId);
        $project = Projects::findOne($problem->projectId);
        $user = User::findOne($project->userId);
        $formCreateGcp = new FormCreateGcp();
        $cache = Yii::$app->cache;

        $count_descInterview = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $id])->andWhere(['not', ['desc_interview_confirm.id' => null]])->count();

        $count_positive = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $id, 'desc_interview_confirm.status' => '1'])->count();


        if (Yii::$app->request->isAjax) {

            if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive && $model->problem->exist_confirm == 1) || (!empty($model->gcps)  && $model->count_positive <= $count_positive && $model->problem->exist_confirm == 1)) {

                $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
                    '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/gcps/formCreate/';
                $cache_form_creation = $cache->get('formCreateGcpCache');

                if ($cache_form_creation) {
                    //Заполнение полей модели FormCreateGcp данными из кэша
                    foreach ($cache_form_creation['FormCreateGcp'] as $key => $value) {
                        $formCreateGcp[$key] = $value;
                    }
                }

                $response =  [
                    'success' => true,
                    'renderAjax' => $this->renderAjax('/gcp/create', [
                        'confirmProblem' => $model,
                        'model' => $formCreateGcp,
                        'segment' => $model->problem->segment,
                    ]),
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            }else{

                $response = ['error' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * Завершение подтверждения ГПС и переход на следующий этап
     * @param $id
     * @return array|bool
     */
    public function actionMovingNextStage($id)
    {
        $model = ConfirmProblem::findOne($id);
        $problem = $model->problem;

        $count_descInterview = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $id])->andWhere(['not', ['desc_interview_confirm.id' => null]])->count();

        $count_positive = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $id, 'desc_interview_confirm.status' => '1'])->count();

        if(Yii::$app->request->isAjax) {

            if (count($model->responds) > $count_descInterview && empty($model->gcps)) {

                $response = ['not_completed_descInterviews' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            }if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->gcps))) {

                $response =  [
                    'success' => true,
                    'exist_confirm' => $problem->exist_confirm,
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            }else{

                $response = ['error' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return bool|Response
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function actionNotExistConfirm($id)
    {
        $model = $this->findModel($id);
        $problem = GenerationProblem::findOne($model->problemId);
        $interview = Interview::findOne($problem->confirmSegmentId);
        $segment = Segment::findOne($problem->segmentId);
        $project = Projects::findOne($problem->projectId);
        $user = User::findOne($project->userId);

        if ($problem->exist_confirm === 0) {

            return $this->redirect(['/generation-problem/index', 'id' => $interview->id]);
        }else {

            $problem->exist_confirm = 0;
            $problem->time_confirm = time();

            if ($problem->save()){

                // Удаление дирректории для кэша подтверждения
                $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
                    '/segments/segment-'.$segment->id. '/problems/problem-'.$problem->id.'/confirm';
                if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                $problem->trigger(GenerationProblem::EVENT_CLICK_BUTTON_CONFIRM);
                return $this->redirect(['/generation-problem/index', 'id' => $interview->id]);
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return bool|Response
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function actionExistConfirm($id)
    {
        $model = $this->findModel($id);
        $problem = GenerationProblem::findOne($model->problemId);
        $segment = Segment::findOne($problem->segmentId);
        $project = Projects::findOne($problem->projectId);
        $user = User::findOne($project->userId);

        $problem->exist_confirm = 1;
        $problem->time_confirm = time();

        if ($problem->save()){

            // Удаление дирректории для кэша подтверждения
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
                '/segments/segment-'.$segment->id. '/problems/problem-'.$problem->id.'/confirm';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            $problem->trigger(GenerationProblem::EVENT_CLICK_BUTTON_CONFIRM);
            return $this->redirect(['/gcp/index', 'id' => $model->id]);
        }
        return false;
    }


    /**
     * @param $id
     */
    public function actionSaveCacheCreationForm($id)
    {
        $problem = GenerationProblem::findOne($id);
        $segment = Segment::findOne($problem->segmentId);
        $project = Projects::findOne($problem->projectId);
        $user = User::findOne($project->userId);
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $data = $_POST; //Массив, который будем записывать в кэш
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
                '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/confirm/formCreateConfirm/';
            $key = 'formCreateConfirmProblemCache'; //Формируем ключ
            $cache->set($key, $data, 3600*24*30); //Создаем файл кэша на 30дней
        }
    }


    /**
     * @param $id
     * @return string|Response
     */
    public function actionCreate($id)
    {
        $model = new FormCreateConfirmProblem();
        $problem = GenerationProblem::findOne($id);
        $confirmSegment = Interview::findOne($problem->confirmSegmentId);
        $segment = Segment::findOne($confirmSegment->segmentId);
        $project = Projects::findOne($segment->projectId);
        $user = User::findOne($project->userId);
        $cache = Yii::$app->cache;

        //кол-во представителей сегмента
        $count_represent_segment = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $confirmSegment->id, 'desc_interview.status' => '1'])->count();

        $model->setCountRespond($count_represent_segment);

        $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
            '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/confirm/formCreateConfirm/';
        $cache_form_creation = $cache->get('formCreateConfirmProblemCache');

        if ($cache_form_creation) { //Если существует кэш, то добавляем его к полям модели FormCreateConfirmProblem
            foreach ($cache_form_creation['FormCreateConfirmProblem'] as $key => $value) {
                $model[$key] = $value;
            }
        }

        if ($problem->confirm){
            //Если у проблемы создана программа подтверждения, то перейти на страницу подтверждения
            return $this->redirect(['view', 'id' => $problem->confirm->id]);
        }


        return $this->render('create', [
            'model' => $model,
            'problem' => $problem,
            'confirmSegment' => $confirmSegment,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    /**
     * @param $id
     * @return array|bool
     * @throws ErrorException
     * @throws NotFoundHttpException
     */
    public function actionSaveConfirmProblem($id)
    {
        $model = new FormCreateConfirmProblem();
        $model->setHypothesisId($id);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model = $model->create()){

                    $response =  ['success' => true, 'id' => $model->id];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionUpdate ($id)
    {
        $model = new FormUpdateConfirmProblem($id);
        $problem = GenerationProblem::findOne($id);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model = $model->update()){

                    $response = [
                        'success' => true,
                        'ajax_data_confirm' => $this->renderAjax('ajax_data_confirm', [
                            'formUpdateConfirmProblem' => new FormUpdateConfirmProblem($id),
                            'model' => $model, 'problem' => $problem
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }

    /**
     * Страница со списком вопросов
     * @param $id
     * @return string
     */
    public function actionAddQuestions($id)
    {
        $model = ConfirmProblem::findOne($id);
        $formUpdateConfirmProblem = new FormUpdateConfirmProblem($id);
        $problem = GenerationProblem::findOne($model->gps_id);
        $interview = Interview::findOne($problem->confirmSegmentId);
        $segment = Segment::findOne($problem->segmentId);
        $project = Projects::findOne($problem->projectId);
        $questions = QuestionsConfirmProblem::findAll(['confirm_problem_id' => $id]);
        $newQuestion = new QuestionsConfirmProblem();

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('add-questions', [
            'formUpdateConfirmProblem' => $formUpdateConfirmProblem,
            'questions' => $questions,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
            'model' => $model,
            'problem' => $problem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * @param $id
     * @return array|bool
     */
    public function actionAddQuestion($id)
    {
        $model = new QuestionsConfirmProblem();
        $model->confirm_problem_id = $id;

        if ($model->load(Yii::$app->request->post())){

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $confirmProblemNew = ConfirmProblem::findOne($id);
                    $questions = $confirmProblemNew->questions;

                    //Создание пустого ответа для нового вопроса для каждого респондента
                    $confirmProblemNew->addAnswerConfirmProblem($model->id);
                    //Добавляем вопрос в общую базу вопросов
                    $confirmProblemNew->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $confirmProblemNew->queryQuestionsGeneralList();

                    $response = [
                        'model' => $model,
                        'questions' => $questions,
                        'queryQuestions' => $queryQuestions,
                        'ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function actionGetQueryQuestions ($id)
    {
        $confirmProblem = $this->findModel($id);
        $questions = $confirmProblem->questions;

        if(Yii::$app->request->isAjax) {
            $response = ['ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function actionGetQuestionUpdateForm ($id)
    {
        $model = new FormUpdateQuestionConfirmProblem($id);
        $confirmProblem = $this->findModel($model->confirm_problem_id);
        $questions = $confirmProblem->questions;

        if(Yii::$app->request->isAjax) {

            $response = [
                'ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions]),
                'renderAjax' => $this->renderAjax('ajax_form_update_question', ['model' => $model]),
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function actionUpdateQuestion ($id)
    {
        $model = new FormUpdateQuestionConfirmProblem($id);

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($model = $model->update()) {

                    $confirmProblem = $this->findModel($model->confirm_problem_id);
                    $questions = $confirmProblem->questions;

                    //Добавляем вопрос в общую базу вопросов
                    $confirmProblem->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $confirmProblem->queryQuestionsGeneralList();

                    $response = [
                        'model' => $model,
                        'questions' => $questions,
                        'queryQuestions' => $queryQuestions,
                        'ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }

    /**
     * @param $id
     * @return array|bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteQuestion($id)
    {
        $model = QuestionsConfirmProblem::findOne($id);

        if(Yii::$app->request->isAjax) {

            if ($model->delete()){

                $confirmProblemNew = ConfirmProblem::findOne(['id' => $model->confirm_problem_id]);
                $questions = $confirmProblemNew->questions;

                //Удаление ответов по данному вопросу у всех респондентов данного подтверждения
                $confirmProblemNew->deleteAnswerConfirmProblem($id);

                //Передаем обновленный список вопросов для добавления в программу
                $queryQuestions = $confirmProblemNew->queryQuestionsGeneralList();

                $response = [
                    'model' => $model,
                    'questions' => $questions,
                    'queryQuestions' => $queryQuestions,
                    'ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions]),
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetDataQuestionsAndAnswers($id)
    {
        $model = $this->findModel($id);
        $questions = $model->questions;

        $response = ['ajax_questions_and_answers' => $this->renderAjax('ajax_questions_and_answers', ['questions' => $questions])];
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = $response;
        return $response;

    }


    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    public function actionMpdfQuestionsAndAnswers($id)
    {
        $model = $this->findModel($id);
        $questions = $model->questions;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/confirm-problem/questions_and_answers_pdf', ['questions' => $questions]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $problem_desc = $model->problem->description;
        if (mb_strlen($problem_desc) > 25) {
            $problem_desc = mb_substr($problem_desc, 0, 25) . '...';
        }

        $filename = 'Ответы респондентов на вопросы интервью для подтверждения проблемы: «'.$problem_desc.'».';

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            //'format' => Pdf::FORMAT_TABLOID,
            // portrait orientation
            //'orientation' => Pdf::ORIENT_LANDSCAPE,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => $destination,
            'filename' => $filename,
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssFile' => '@app/web/css/style.css',
            // any css to be embedded if required
            //'cssInline' => '.business-model-view-export {color: #3c3c3c;};',
            'marginTop' => 20,
            'marginBottom' => 20,
            'marginFooter' => 5,
            'defaultFont' => 'RobotoCondensed-Light',
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => $filename,
                'SetHeader' => ['<div style="color: #3c3c3c;">Ответы респондентов на вопросы интервью. Проблема: «'.$problem_desc.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
                'SetFooter' => ['<div style="color: #3c3c3c;">Страница {PAGENO}</div>'],
                //'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                //'SetAuthor' => 'Kartik Visweswaran',
                //'SetCreator' => 'Kartik Visweswaran',
                //'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }


    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    public function actionMpdfDataResponds($id)
    {
        $model = $this->findModel($id);
        $responds = $model->responds;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/confirm-problem/viewpdf', ['model' => $model, 'responds' => $responds]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $problem_desc = $model->problem->description;
        if (mb_strlen($problem_desc) > 25) {
            $problem_desc = mb_substr($problem_desc, 0, 25) . '...';
        }

        $filename = 'Подтверждение проблемы «'.$problem_desc.'». Таблица респондентов.pdf';

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            //'format' => Pdf::FORMAT_TABLOID,
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            //'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => $destination,
            'filename' => $filename,
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssFile' => '@app/web/css/style.css',
            // any css to be embedded if required
            //'cssInline' => '.business-model-view-export {color: #3c3c3c;};',
            'marginFooter' => 5,
            'defaultFont' => 'RobotoCondensed-Light',
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => ['Респонденты для подтверждения гипотезы проблемы «'.$problem_desc.'»'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Респонденты для подтверждения гипотезы проблемы «'.$problem_desc.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
                'SetFooter' => ['<div style="color: #3c3c3c;">Страница {PAGENO}</div>'],
                //'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                //'SetAuthor' => 'Kartik Visweswaran',
                //'SetCreator' => 'Kartik Visweswaran',
                //'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    /**
     * Finds the ConfirmProblem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ConfirmProblem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConfirmProblem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
