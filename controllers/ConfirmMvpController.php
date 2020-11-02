<?php

namespace app\controllers;

use app\models\ConfirmGcp;
use app\models\ConfirmProblem;
use app\models\DescInterviewMvp;
use app\models\FormUpdateConfirmMvp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Mvp;
use app\models\Projects;
use app\models\QuestionsConfirmMvp;
use app\models\RespondsGcp;
use app\models\RespondsMvp;
use app\models\Segment;
use app\models\UpdateRespondMvpForm;
use app\models\User;
use kartik\mpdf\Pdf;
use Yii;
use app\models\ConfirmMvp;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


class ConfirmMvpController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

            $model = ConfirmMvp::findOne(Yii::$app->request->get());
            $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
            $project = Projects::find()->where(['id' => $mvp->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = ConfirmMvp::findOne(Yii::$app->request->get());
            $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
            $project = Projects::find()->where(['id' => $mvp->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $mvp = Mvp::findOne(Yii::$app->request->get());
            $project = Projects::find()->where(['id' => $mvp->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['save-confirm-mvp'])){

            $mvp = Mvp::findOne(Yii::$app->request->get());
            $project = Projects::find()->where(['id' => $mvp->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['add-questions'])){

            $model = ConfirmMvp::findOne(Yii::$app->request->get());
            $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
            $project = Projects::find()->where(['id' => $mvp->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif (in_array($action->id, ['delete-question'])){

            $question = QuestionsConfirmMvp::findOne(Yii::$app->request->get());
            $confirm_mvp = ConfirmMvp::find()->where(['id' => $question->confirm_mvp_id])->one();
            $mvp = Mvp::find()->where(['id' => $confirm_mvp->mvp_id])->one();
            $project = Projects::find()->where(['id' => $mvp->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
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
        $formUpdateConfirmMvp = new FormUpdateConfirmMvp($id);
        $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $responds = RespondsMvp::find()->where(['confirm_mvp_id' => $id])->all();
        $questions = QuestionsConfirmMvp::find()->where(['confirm_mvp_id' => $id])->all();

        $newQuestion = new QuestionsConfirmMvp();
        $newQuestion->confirm_mvp_id = $id;

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        $newRespond = new RespondsMvp();
        $newRespond->confirm_mvp_id = $model->id;

        $updateRespondForms = [];
        $createDescInterviewForms = [];
        $updateDescInterviewForms = [];

        foreach ($responds as $i => $respond) {

            $updateRespondForms[] = new UpdateRespondMvpForm($respond->id);

            $createDescInterviewForms[] = new DescInterviewMvp();

            $updateDescInterviewForms[] = $respond->descInterview;
        }


        return $this->render('view', [
            'model' => $model,
            'formUpdateConfirmMvp' => $formUpdateConfirmMvp,
            'mvp' => $mvp,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'responds' => $responds,
            'questions' => $questions,
            'newQuestion' => $newQuestion,
            'newRespond' => $newRespond,
            'queryQuestions' => $queryQuestions,
            'updateRespondForms' => $updateRespondForms,
            'createDescInterviewForms' => $createDescInterviewForms,
            'updateDescInterviewForms' => $updateDescInterviewForms,
        ]);
    }

    /**
     * Проверка данных подтверждения на этапе генерации бизнес-модели
     * @param $id
     * @return array
     */
    public function actionDataAvailabilityForNextStep($id)
    {
        $model = ConfirmMvp::findOne($id);

        $count_descInterview = 0;
        $count_positive = 0;

        foreach ($model->responds as $respond) {

            if ($respond->descInterview){
                $count_descInterview++;

                if ($respond->descInterview->status == 1){
                    $count_positive++;
                }
            }
        }

        if(Yii::$app->request->isAjax) {
            if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->business)  && $model->count_positive <= $count_positive)) {

                $response =  ['success' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }else{

                $response = ['error' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
    }


    /**
     * Завершение подтверждения MVP и переход на следующий этап
     * @param $id
     * @return array
     */
    public function actionMovingNextStage($id)
    {
        $model = ConfirmMvp::findOne($id);
        $mvp = $model->mvp;

        $count_descInterview = 0;
        $count_positive = 0;

        foreach ($model->responds as $respond) {

            if ($respond->descInterview){
                $count_descInterview++;

                if ($respond->descInterview->status == 1){
                    $count_positive++;
                }
            }
        }

        if(Yii::$app->request->isAjax) {

            if (count($model->responds) > $count_descInterview && empty($model->business)) {

                $response = ['not_completed_descInterviews' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->business))) {

                $response =  [
                    'success' => true,
                    'exist_confirm' => $mvp->exist_confirm,
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }else{

                $response = ['error' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
    }


    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionNotExistConfirm($id)
    {
        $model = ConfirmMvp::findOne($id);
        $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($mvp->exist_confirm === 0) {

            return $this->redirect(['mvp/index', 'id' => $confirmGcp->id]);
        }else {

            $mvp->exist_confirm = 0;
            $mvp->time_confirm = time();

            if ($mvp->save()){

                $project->updated_at = time();
                if ($project->save()){
                    return $this->redirect(['mvp/index', 'id' => $confirmGcp->id]);
                }
            }
        }
    }


    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionExistConfirm($id)
    {
        $model = ConfirmMvp::findOne($id);
        $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $mvp->exist_confirm = 1;
        $mvp->time_confirm = time();

        if ($mvp->save()){

            $project->updated_at = time();
            if ($project->save()){
                return $this->redirect(['business-model/create', 'id' => $model->id]);
            }
        }
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $model = new ConfirmMvp();
        $model->mvp_id = $id;

        $mvp = Mvp::findOne($id);
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $responds = RespondsGcp::find()->where(['confirm_gcp_id' => $confirmGcp->id])->all();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if (!empty($mvp->confirm)){
            return $this->redirect(['view', 'id' => $mvp->confirm->id]);
        }

        $respondsPre = []; // респонденты, кот-е подтвердили ЦП
        foreach ($responds as $respond){
            if ($respond->descInterview->status == 1){
                $respondsPre[] = $respond;
            }
        }

        $model->count_respond = count($respondsPre);

        return $this->render('create', [
            'model' => $model,
            'mvp' => $mvp,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    /**
     * @param $id
     * @return array|\yii\web\Response
     */
    public function actionSaveConfirmMvp($id)
    {
        $model = new ConfirmMvp();
        $model->mvp_id = $id;

        $mvp = Mvp::findOne($id);
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $responds = RespondsGcp::find()->where(['confirm_gcp_id' => $confirmGcp->id])->all();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if (!empty($mvp->confirm)){
            return $this->redirect(['view', 'id' => $mvp->confirm->id]);
        }

        foreach ($responds as $respond){
            if ($respond->descInterview->status == 1){
                $respondsPre[] = $respond;
            }
        }

        $model->count_respond = count($respondsPre);


        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->count_respond >= $model->count_positive && $model->count_positive > 0){

                    if ($model->save()) {

                        //Создание респондентов для программы подтверждения MVP из респондентов подтвердивших ЦП
                        $model->createRespondConfirm($responds);

                        //Вопросы, которые будут добавлены по-умолчанию
                        $model->addQuestionDefault('Что нравится в представленном MVP?');
                        $model->addQuestionDefault('Что не нравится в представленном MVP?');
                        $model->addQuestionDefault('Чем отличается ожидаемое решение от представленного?');
                        $model->addQuestionDefault('Что бы Вы хотели сделать по другому?');
                        $model->addQuestionDefault('Что показалось неудобным?');
                        $model->addQuestionDefault('Вы готовы заплатить за такой продукт?');

                        $project->updated_at = time();

                        if ($project->save()) {

                            $response =  [
                                'success' => true,
                                'id' => $model->id,
                            ];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    }
                } else {

                    $response =  [
                        'error' => true,
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }

            }
        }
    }


    /**
     * Страница со списком вопросов
     * @param $id
     * @return string
     */
    public function actionAddQuestions($id)
    {
        $confirmMvp = ConfirmMvp::findOne($id);
        $formUpdateConfirmMvp = new FormUpdateConfirmMvp($id);
        $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $interview = Interview::findOne(['id' => $problem->interview_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $questions = QuestionsConfirmMvp::find()->where(['confirm_mvp_id' => $id])->all();

        $newQuestion = new QuestionsConfirmMvp();
        $newQuestion->confirm_mvp_id = $id;

        //Список вопросов для добавления к списку программы
        $queryQuestions = $confirmMvp->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('add-questions', [
            'formUpdateConfirmMvp' => $formUpdateConfirmMvp,
            'confirmMvp' => $confirmMvp,
            'mvp' => $mvp,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
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
     * Метод для добавления новых вопросов
     * @param $id
     * @return array
     */
    public function actionAddQuestion($id)
    {
        $model = new QuestionsConfirmMvp();
        $model->confirm_mvp_id = $id;
        $confirmMvp = ConfirmMvp::findOne($id);
        $mvp = Gcp::findOne(['id' => $confirmMvp->mvp_id]);
        $project = Projects::findOne(['id' => $mvp->project_id]);


        if ($model->load(Yii::$app->request->post())){

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $confirmMvpNew = ConfirmMvp::findOne($id);
                    $questions = $confirmMvpNew->questions;

                    //Создание пустого ответа для нового вопроса для каждого респондента
                    $confirmMvpNew->addAnswerConfirmMvp($model->id);
                    //Добавляем вопрос в общую базу вопросов
                    $confirmMvpNew->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $confirmMvpNew->queryQuestionsGeneralList();

                    $project->updated_at = time();
                    $project->save();

                    $response = [
                        'model' => $model,
                        'questions' => $questions,
                        'queryQuestions' => $queryQuestions,
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    /**
     * @param $id
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteQuestion($id)
    {
        $model = QuestionsConfirmMvp::findOne($id);
        $confirmMvp = ConfirmMvp::find()->where(['id' => $model->confirm_mvp_id])->one();
        $mvp = Gcp::findOne(['id' => $confirmMvp->mvp_id]);
        $project = Projects::findOne(['id' => $mvp->project_id]);

        if(Yii::$app->request->isAjax) {

            if ($model->delete()){

                $project->updated_at = time();
                $project->save();

                $confirmMvp = ConfirmMvp::findOne(['id' => $model->confirm_mvp_id]);
                $questions = $confirmMvp->questions;

                //Удаление ответов по данному вопросу у всех респондентов данного подтверждения
                $confirmMvp->deleteAnswerConfirmMvp($id);
                //Передаем обновленный список вопросов для добавления в программу
                $queryQuestions = $confirmMvp->queryQuestionsGeneralList();

                $response = [
                    'questions' => $questions,
                    'queryQuestions' => $queryQuestions,
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
    }


    /**
     * @param $id
     * @return array
     */
    public function actionUpdate($id)
    {
        $model = new FormUpdateConfirmMvp($id);
        $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();


        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->count_respond >= $model->count_positive && $model->count_positive > 0){

                    if ($confirm_mvp = $model->update()){

                        $project->updated_at = time();

                        if ($project->save()){

                            $descInterviews = [];
                            foreach ($confirm_mvp->responds as $respond) {
                                if($respond->descInterview) {
                                    $descInterviews[] = $respond->descInterview;
                                }
                            }

                            $response = [
                                'model' => $confirm_mvp,
                                'responds' => $confirm_mvp->responds,
                                'descInterviews' => $descInterviews,
                                'business-model' => $confirm_mvp->business,
                                'mvp' => $mvp,
                            ];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    }
                }else{

                    $response = ['error' => true];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    /**
     * @param $id
     * @return mixed
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionMpdfDataResponds($id)
    {
        $model = ConfirmMvp::findOne($id);
        $responds = $model->responds;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/confirm-mvp/viewpdf', ['model' => $model, 'responds' => $responds]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $mvp_desc = $model->mvp->description;
        if (mb_strlen($mvp_desc) > 25) {
            $mvp_desc = mb_substr($mvp_desc, 0, 25) . '...';
        }

        $filename = 'Подтверждение MVP: «'.$mvp_desc.'». Таблица респондентов.pdf';

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
                'SetTitle' => ['Респонденты для подтверждения MVP: «'.$mvp_desc.'»'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Респонденты для подтверждения гипотезы MVP: «'.$mvp_desc.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
     * Deletes an existing ConfirmMvp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $model =$this->findModel($id);
        $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->updated_at = time();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Удаление доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        if ($model->delete()) {
            $project->save();
        }

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the ConfirmMvp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ConfirmMvp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConfirmMvp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
